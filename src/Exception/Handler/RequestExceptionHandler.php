<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsExt utils.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 * @license  https://github.com/firecms-ext/utils/blob/master/LICENSE
 */
namespace FirecmsExt\Utils\Exception\Handler;

use FirecmsExt\Utils\Amqp\Producer\AppException\NotifyProducer;
use FirecmsExt\Utils\Amqp\Producer\Log\ExceptionLogProducer;
use FirecmsExt\Utils\Constants\HttpCode;
use Hyperf\Amqp\Producer;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\RpcClient\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class RequestExceptionHandler extends ExceptionHandler
{
    public function handle(\Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        $message = HttpCode::getMessage(HttpCode::NOT_FOUND);
        if (env('APP_ENV') == 'dev') {
            $data = make(RequestInterface::class)->getServerParams();
            $message = $throwable->getMessage();
            var_dump($message);
            var_dump($data);

            echo PHP_EOL;
            var_dump('【' . get_class($throwable) . '】' . sprintf(
                '%s[%s] in %s',
                $throwable->getMessage(),
                $throwable->getLine(),
                $throwable->getFile()
            ));
            echo $throwable->getTraceAsString();
            echo PHP_EOL;
        }

        $data = [
            'throwable' => get_class($throwable),
            'subject' => '【' . config('app_name', 'firecms') . '】异常通知',
            'message' => $throwable->getMessage(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'traces' => $throwable->getTrace(),
            'trace_string' => $throwable->getTraceAsString(),
        ];

        // 异常通知推送
        app()->get(Producer::class)->produce(new NotifyProducer($data));
        // 异常日志推送
        app()->get(Producer::class)->produce(new ExceptionLogProducer($data));

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Server', 'Hyperf Firecms ' . ucwords(str_replace('_', ' ', env('APP_NAME'))))
            ->withStatus(method_exists($throwable, 'getStatusCode') ? $throwable->getStatusCode() : HttpCode::NOT_FOUND)
            ->withBody(new SwooleStream(json_encode([
                'message' => $message,
            ])));
    }

    public function isValid(\Throwable $throwable): bool
    {
        return $throwable instanceof RequestException;
    }
}
