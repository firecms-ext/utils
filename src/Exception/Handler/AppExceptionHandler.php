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
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

class AppExceptionHandler extends ExceptionHandler
{
    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(\Throwable $throwable, ResponseInterface $response)
    {
        $message = 'Internal Server Error.';
        if (env('APP_ENV') == 'dev') {
            var_dump('AppExceptionEvent');
            $message = sprintf(
                '%s[%s] in %s',
                $throwable->getMessage(),
                $throwable->getLine(),
                $throwable->getFile()
            );
            echo PHP_EOL;
            var_dump('【' . get_class($throwable) . '】' . $message);
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
            ->withStatus(method_exists($throwable, 'getStatusCode') ? $throwable->getStatusCode() : HttpCode::SERVER_ERROR)
            ->withBody(new SwooleStream(json_encode([
                'message' => $message,
            ])));
    }

    public function isValid(\Throwable $throwable): bool
    {
        return true;
    }
}
