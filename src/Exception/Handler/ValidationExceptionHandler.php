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
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;

class ValidationExceptionHandler extends ExceptionHandler
{
    public function handle(\Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();
        /** @var ValidationException $throwable */
        $errors = $throwable->validator->errors();
        if (env('APP_ENV') == 'dev') {
            var_dump($errors->getMessages());
        }

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Server', 'Hyperf Firecms ' . ucwords(str_replace('_', ' ', env('APP_NAME'))))
            ->withStatus(method_exists($throwable, 'getStatusCode') ? $throwable->getStatusCode() : HttpCode::UNPROCESSABLE_ENTITY)
            ->withBody(new SwooleStream(json_encode([
                'message' => $errors->first(),
                'errors' => $errors->getMessages(),
            ])));
    }

    public function isValid(\Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
