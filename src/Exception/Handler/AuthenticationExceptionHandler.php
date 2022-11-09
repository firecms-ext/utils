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
use FirecmsExt\Utils\Exception\AuthenticationException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

class AuthenticationExceptionHandler extends ExceptionHandler
{
    public function handle(\Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Server', 'Hyperf Firecms ' . ucwords(str_replace('_', ' ', env('APP_NAME'))))
            ->withStatus(HttpCode::UNAUTHORIZED)
            ->withBody(new SwooleStream(json_encode([
                'message' => HttpCode::getMessage(HttpCode::UNAUTHORIZED),
            ])));
    }

    public function isValid(\Throwable $throwable): bool
    {
        return $throwable instanceof AuthenticationException;
    }
}
