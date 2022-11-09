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
namespace FirecmsExt\Utils\Middleware;

use FirecmsExt\Utils\Amqp\Producer\Log\RequestLogProducer;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcServiceInterface;
use Hyperf\Amqp\Producer;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HttpMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 执行之前
        $result = $handler->handle($request);
        // 执行之后
        $user = app()->get(AuthRpcServiceInterface::class)
            ->user($request->getHeader('authorization'));

        app()->get(Producer::class)->produce(new RequestLogProducer([
            'user_id' => $user['id'] ?? null,
            'username' => $user['username'] ?? null,
            'url' => $request->getRequestTarget(),
            'method' => $request->getMethod(),
            'route' => $request->getAttribute(Dispatched::class)->handler ?? null,
            'query_params' => $request->getQueryParams() ?: null,
            'body_params' => $request->getParsedBody() ?: null,
            'upload_files' => $request->getUploadedFiles(),
            'headers' => $request->getHeaders() ?: null,
        ]));

        return $result->withHeader('Server', 'Hyperf Firecms ' . ucwords(str_replace(['_', '-'], ' ', env('APP_NAME'))));
    }
}
