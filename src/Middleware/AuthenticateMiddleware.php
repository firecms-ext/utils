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

use FirecmsExt\Utils\Exception\AuthenticationException;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcServiceInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticateMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! app()->get(AuthRpcServiceInterface::class)->check($request->getHeader('authorization'))) {
            $this->authenticate($request, $this->guards());
        }

        return $handler->handle($request);
    }

    protected function guards(): array
    {
        return [
            'guard' => 'api',
        ];
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @throws AuthenticationException
     */
    protected function authenticate(ServerRequestInterface $request, array $guards): void
    {
        ! $this->passable() and $this->unauthenticated($request, $guards);
    }

    /**
     * Determines whether an unauthenticated user can pass the auth guard.
     * In general, this should be return FALSE. However, in some special cases,
     * return TRUE would be useful.
     */
    protected function passable(): bool
    {
        return false;
    }

    /**
     * Handle an unauthenticated user.
     *
     * @throws AuthenticationException
     */
    protected function unauthenticated(ServerRequestInterface $request, array $guards): void
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(ServerRequestInterface $request): ?string
    {
        return null;
    }
}
