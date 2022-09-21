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

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\IdGeneratorInterface;
use Psr\SimpleCache\CacheInterface;

if (! function_exists('cache')) {
    /**
     * Cache 对象
     */
    function cache(): CacheInterface
    {
        return make(CacheInterface::class);
    }
}

if (! function_exists('redis')) {
    /**
     * Redis 对象
     */
    function redis(): Redis
    {
        return make(Redis::class);
    }
}

if (! function_exists('request')) {
    /**
     * Request 对象
     */
    function request(): RequestInterface
    {
        return make(RequestInterface::class);
    }
}

if (! function_exists('response')) {
    /**
     * Response 对象
     */
    function response(): ResponseInterface
    {
        return make(ResponseInterface::class);
    }
}

if (! function_exists('generateId')) {
    /**
     * ID 生成.
     */
    function generateId(): string
    {
        return (string) make(IdGeneratorInterface::class)->generate();
    }
}

if (! function_exists('age')) {
    /**
     * 年龄.
     */
    function age(string $birthday): int
    {
        [$year, $month, $day] = explode('-', $birthday);
        $age = date('Y') - $year;
        $month_diff = date('m') - $month;
        $day_diff = date('d') - $day;
        if ($day_diff < 0 || $month_diff < 0) {
            --$age;
        }
        return (int) $age;
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
     */
    function toTree(array $rows, string $pid = 'parent_id', string $id = 'id', string $children = 'children'): array
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
     * 净化 HTML 字符串.
     */
    function purifyHtml(string $html, HTMLPurifier_Config $config = null): string
    {
        return (new HTMLPurifier($config ?: HTMLPurifier_Config::createDefault()))->purify($html);
    }
}
