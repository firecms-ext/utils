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

use FirecmsExt\Utils\Constants\HttpCode;
use Hyperf\Database\Exception\QueryException;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

class QueryExceptionHandler extends ExceptionHandler
{
    public int $code;

    public function handle(\Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();

        $message = HttpCode::getMessage($this->code);
        if (env('APP_ENV') == 'dev') {
            $message = $throwable->getMessage();
            var_dump($message);
        }

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Server', 'Hyperf Firecms ' . ucwords(str_replace('_', ' ', env('APP_NAME'))))
            ->withStatus(method_exists($throwable, 'getStatusCode') ? $throwable->getStatusCode() : $this->code)
            ->withBody(new SwooleStream(json_encode([
                'message' => $message,
            ])));
    }

    public function isValid(\Throwable $throwable): bool
    {
        if ($throwable instanceof ModelNotFoundException) {
            $this->code = HttpCode::NOT_FOUND;
            return true;
        }
        if ($throwable instanceof QueryException) {
            $this->code = HttpCode::NOT_IMPLEMENTED;
            return true;
        }

        return false;
    }
}
