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
namespace FirecmsExt\Utils\Service;

use Hyperf\HttpServer\MiddlewareManager;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Hyperf\HttpServer\Router\RouteCollector;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Str;
use Psr\Http\Message\ServerRequestInterface;

class RouteParseService
{
    /**
     * 当前路由。
     */
    public function current(): Handler
    {
        return $this->dispatched()->handler;
    }

    /**
     * 解析。
     */
    public function dispatched(): Dispatched
    {
        return ApplicationContext::getContainer()
            ->get(ServerRequestInterface::class)
            ->getAttribute(Dispatched::class);
    }

    /**
     * 解析。
     */
    public function parse(?string $path = null, string $server = 'http'): array
    {
        $factory = ApplicationContext::getContainer()->get(DispatcherFactory::class);
        $router = $factory->getRouter($server);

        return $this->rows($this->analyzeRouter($server, $router, $path));
    }

    protected function analyzeRouter(string $server, RouteCollector $router, ?string $path): array
    {
        $data = [];
        [$staticRouters, $variableRouters] = $router->getData();
        foreach ($staticRouters as $method => $items) {
            foreach ($items as $handler) {
                $this->analyzeHandler($data, $server, $method, $path, $handler);
            }
        }
        foreach ($variableRouters as $method => $items) {
            foreach ($items as $item) {
                if (is_array($item['routeMap'] ?? false)) {
                    foreach ($item['routeMap'] as $routeMap) {
                        $this->analyzeHandler($data, $server, $method, $path, $routeMap[0]);
                    }
                }
            }
        }
        return $data;
    }

    protected function analyzeHandler(array &$data, string $serverName, string $method, ?string $path, Handler $handler): void
    {
        $uri = $handler->route;
        if (! is_null($path) && ! Str::contains($uri, $path)) {
            return;
        }
        if (is_array($handler->callback)) {
            $action = $handler->callback[0] . '::' . $handler->callback[1];
        } elseif (is_string($handler->callback)) {
            $action = $handler->callback;
        } elseif (is_callable($handler->callback)) {
            $action = 'Closure';
        } else {
            $action = (string) $handler->callback;
        }
        $unique = "{$serverName}|{$uri}|{$action}";

        if (isset($data[$unique])) {
            $data[$unique]['method'][] = $method;
        } else {
            // method,uri,name,action,name

            $registeredMiddlewares = MiddlewareManager::get($serverName, $uri, $method);
            $middlewares = config('middlewares.' . $serverName, []);
            $middlewares = array_merge($middlewares, $registeredMiddlewares);

            if ($name = ($handler->options['name'] ?? null)) {
                $data[$unique] = [
                    'server' => $serverName,
                    'method' => [$method],
                    'uri' => $uri,
                    'action' => $action,
                    'name' => implode('/', array_values(array_filter((array) $name))),
                ];
            }
        }
    }

    private function rows(array $data): array
    {
        $rows = [];
        foreach ($data as $route) {
            $route['method'] = implode('|', $route['method']);
            $rows[] = $route;
        }

        return array_slice($rows, 0, count($rows) - 1);
    }
}
