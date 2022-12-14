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
namespace FirecmsExt\Utils\JsonRpc\Consumer;

class ConstantRpcService extends AbstractServiceClient implements ConstantRpcServiceInterface
{
    public function all(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function options(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function items(string $category_name): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function names(string $category_name): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function values(string $category_name): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function value(string $category_name, string $name): ?int
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function name(string $category_name, int $value): ?string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function alias(string $category_name, int $value): ?string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function message(string $category_name, int $value): ?string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function title(string $category_name, int $value): ?string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
