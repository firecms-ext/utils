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
use Carbon\Carbon;
use Hyperf\Database\Model\Builder;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\CacheInterface;

if (! function_exists('app')) {
    /**
     * App 容器 对象
     */
    function app(): ?ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}

if (! function_exists('cache')) {
    /**
     * Cache 对象
     */
    function cache(): CacheInterface
    {
        return app()->get(CacheInterface::class);
    }
}

if (! function_exists('redis')) {
    /**
     * Redis 对象
     */
    function redis(): Redis
    {
        return app()->get(Redis::class);
    }
}

if (! function_exists('request')) {
    /**
     * Request 对象
     */
    function request(): RequestInterface
    {
        return app()->get(RequestInterface::class);
    }
}

if (! function_exists('response')) {
    /**
     * Response 对象
     */
    function response(): ResponseInterface
    {
        return app()->get(ResponseInterface::class);
    }
}

if (! function_exists('generateId')) {
    /**
     * ID 生成.
     */
    function generateId(): string
    {
        return (string) app()->get(IdGeneratorInterface::class)->generate();
    }
}

if (! function_exists('getRealIp')) {
    /**
     * 获取请求 IP.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function getRealIp(): string
    {
        $request = app()->get(RequestInterface::class);
        $headers = $request->getHeaders();

        if (isset($headers['x-forwarded-for'][0]) && ! empty($headers['x-forwarded-for'][0])) {
            return $headers['x-forwarded-for'][0];
        }
        if (isset($headers['x-real-ip'][0]) && ! empty($headers['x-real-ip'][0])) {
            return $headers['x-real-ip'][0];
        }

        $serverParams = $request->getServerParams();
        if (isset($res['http_client_ip'])) {
            return $serverParams['http_client_ip'];
        }
        if (isset($serverParams['http_x_real_ip'])) {
            return $serverParams['http_x_real_ip'];
        }
        if (isset($serverParams['http_x_forwarded_for'])) {
            // 部分CDN会获取多层代理IP，所以转成数组取第一个值
            $arr = explode(',', $serverParams['http_x_forwarded_for']);
            return $arr[0];
        }
        return $serverParams['remote_addr'];
    }
}

if (! function_exists('age')) {
    /**
     * 年龄.
     */
    function age(string $birthday): int
    {
        return Carbon::parse($birthday)->diffInYears();
    }
}

if (! function_exists('uuid')) {
    /**
     * uuid 生成.
     */
    function uuid(string $prefix = ''): string
    {
        $chars = md5(uniqid((string) mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
        return $prefix . $uuid;
    }
}

if (! function_exists('filesizeFormat')) {
    /**
     * 格式化文件大小.
     */
    function filesizeFormat(int $filesize): string
    {
        if ($filesize > 1099511627776) {
            return floatval(number_format($filesize / 1099511627776, 2)) . ' TB';
        }
        if ($filesize > 1073741824) {
            return floatval(number_format($filesize / 1073741824, 2)) . ' GB';
        }
        if ($filesize > 1048576) {
            return floatval(number_format($filesize / 1048576, 2)) . ' MB';
        }
        if ($filesize > 1024) {
            return floatval(number_format($filesize / 1024, 2)) . ' KB';
        }
        if ($filesize > 0) {
            return $filesize . ' b';
        }

        return '0 b';
    }
}

if (! function_exists('toTree')) {
    /**
     * 转树.
     * @deprecated [将在正式版删除]
     */
    function toTree(array $rows, string $pid = 'parent_id', string $id = 'id', string $children = 'children'): array
    {
        return arrayToTree($rows, $pid, $id, $children);
    }
}

if (! function_exists('arrayToTree')) {
    /**
     * 转树.
     */
    function arrayToTree(array $rows, string $pid = 'parent_id', string $id = 'id', string $children = 'children'): array
    {
        $tree = [];
        $items = [];
        foreach ($rows as $item) {
            $item = (array) $item;
            $items[$item[$id]] = $item;
        }
        foreach ($items as $item) {
            if (isset($items[$item[$pid]])) {
                $items[$item[$pid]][$children][] = &$items[$item[$id]];
            } else {
                $tree[] = &$items[$item[$id]];
            }
        }
        return $tree;
    }
}

if (! function_exists('treeToOptions')) {
    /**
     * 树形选项.
     */
    function treeToOptions(array $tree, string $label = 'title', string $value = 'id', string $children = 'children'): array
    {
        $items = [];

        foreach ($tree as $item) {
            if (isset($item[$children])) {
                $items[] = [
                    'value' => (string) $item[$value],
                    'label' => (string) $item[$label],
                    'disabled' => (bool) ($item['disabled'] ?? ! $item['enable']),
                    'children' => treeToOptions($item[$children]),
                ];
            } else {
                $items[] = [
                    'value' => (string) $item[$value],
                    'label' => (string) $item[$label],
                    'disabled' => (bool) ($item['disabled'] ?? ! $item['enable']),
                ];
            }
        }

        return $items;
    }
}

if (! function_exists('options')) {
    /**
     * 下拉选项.
     */
    function options(array $rows, ?string $label = 'title', ?string $value = 'id'): array
    {
        $items = [];

        foreach ($rows as $key => $row) {
            if ($label && $value) {
                $items[] = [
                    'value' => (string) $row[$value],
                    'label' => (string) $row[$label],
                    'disabled' => (bool) ($row['disabled'] ?? ! $row['enable']),
                ];
            } else {
                $items[] = [
                    'value' => (string) $key,
                    'label' => (string) $row,
                ];
            }
        }

        return $items;
    }
}

if (! function_exists('groupOptions')) {
    /**
     * 分组下拉选项.
     */
    function groupOptions(array $groups, ?string $label = 'title', ?string $value = 'id'): array
    {
        $items = [];

        foreach ($groups as $group => $rows) {
            $items[] = [
                'label' => $group,
                'options' => options($rows, $label, $value),
            ];
        }

        return $items;
    }
}

if (! function_exists('purifyHtml')) {
    /**
     * 防范 XSS 安全漏洞
     * 净化 HTML 字符串.
     * http://htmlpurifier.org/docs.
     */
    function purifyHtml(string $html, HTMLPurifier_Config $config = null): string
    {
        return make(HTMLPurifier::class, ['config' => $config ?: HTMLPurifier_Config::createDefault()])
            ->purify($html);
    }
}

if (! function_exists('filename')) {
    /**
     * 从文件路径中提取文件名。
     */
    function filename(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }
}

if (! function_exists('basename')) {
    /**
     * 从文件路径中提取尾随名称。
     */
    function basename(string $path): string
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }
}

if (! function_exists('extension')) {
    /**
     * 从文件路径提取文件扩展名。
     */
    function extension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}

if (! function_exists('andWhere')) {
    /**
     * 并行查询条件。
     */
    function andWhere(Builder $query, array $where): Builder
    {
        foreach ($where as $key => $val) {
            if (is_null($val)) {
                $query = $query->whereNull($key);
            } elseif (is_string($val) && is_int($key)) {
                $query = $query->whereRaw($key);
            } elseif (is_string($val) && Str::upper($val) == 'NOT NULL') {
                $query = $query->whereNotNull($key);
            } elseif (is_string($val) && str_starts_with($val, '<>')) {
                $query = $query->where($key, '<>', substr($val, 2));
            } elseif (is_string($val) && str_starts_with($val, '!=')) {
                $query = $query->where($key, '<>', substr($val, 2));
            } elseif (is_string($val) && str_starts_with($val, '>')) {
                $query = $query->where($key, '>', substr($val, 1));
            } elseif (is_string($val) && str_starts_with($val, '<')) {
                $query = $query->where($key, '<', substr($val, 1));
            } elseif (is_array($val)) {
                $query = $query->whereIn($key, $val);
            } else {
                $query = $query->where($key, $val);
            }
        }
        return $query;
    }
}

if (! function_exists('ignoreWhere')) {
    /**
     * 排除查询条件。
     */
    function ignoreWhere(Builder $query, array $ignore): Builder
    {
        foreach ($ignore as $key => $val) {
            if (is_null($val)) {
                $query = $query->whereNotNull($key);
            } elseif (is_array($val)) {
                $query = $query->whereNotIn($key, $val);
            } else {
                $query = $query->where($key, '<>', $val);
            }
        }
        return $query;
    }
}

if (! function_exists('class_basename')) {
    /**
     * 获取不带命名空间的类名。
     */
    function class_basename(string $class): string
    {
        return basename(str_replace('\\', '/', $class));
    }
}
