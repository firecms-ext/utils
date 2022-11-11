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

class BaseModelRpcService extends AbstractServiceClient implements BaseModelRpcServiceInterface
{
    public function getTableName(string $modelClass, bool $prefix = false): string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getTableColumns(string $modelClass): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getTableFields(string $modelClass): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getPrefix(string $modelClass): string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function create(string $modelClass, array $data): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function update(string $modelClass, string $id, array $data): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function fillData(string $modelClass, array $attributes, array $parent = null): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function batchDataInsert(string $modelClass, array $items, ?array $parent = null): bool
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function find(string $modelClass, string $id, array $with = []): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getData(string $modelClass, array $where = [], array $with = []): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function setData(string $modelClass, array $data, ?array $where = null): ?array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getList(string $modelClass, array $where = [], array $with = [], int $page = 1, int $limit = 20, string|array $orderBy = null): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function validateUnique(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function validateArray(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function validateExists(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function validateDescendant(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
