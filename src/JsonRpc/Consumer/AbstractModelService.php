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

use FirecmsExt\Utils\Service\ModelServiceInterface;

abstract class AbstractModelService extends AbstractServiceClient implements ModelServiceInterface
{
    public function getTableName(string $modelClass, bool $prefix = false): string
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'prefix'));
    }

    public function getTableColumns(string $modelClass): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass'));
    }

    public function getTableFields(string $modelClass): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass'));
    }

    public function getPrefix(string $modelClass): string
    {
        return $this->__request(__FUNCTION__, compact('modelClass'));
    }

    public function create(string $modelClass, array $data): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'data'));
    }

    public function update(string $modelClass, string $id, array $data): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'id', 'data'));
    }

    public function fillData(string $modelClass, array $attributes, array $parent = null): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'attributes', 'parent'));
    }

    public function batchDataInsert(string $modelClass, array $items, ?array $parent = null): bool
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'items', 'parent'));
    }

    public function find(string $modelClass, string $id, array $with = []): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'id', 'with'));
    }

    public function getData(string $modelClass, array $where = [], array $with = []): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'where', 'with'));
    }

    public function setData(string $modelClass, array $data, ?array $where = null): ?array
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'data', 'where'));
    }

    public function getItems(string $modelClass, array $where = [], array $with = [], int $page = 1, int $limit = 20, string|array $orderBy = null): array
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'where', 'with', 'page', 'limit'));
    }

    public function validateUnique(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'attribute', 'value', 'ignore', 'where'));
    }

    public function validateArray(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'attribute', 'value', 'ignore', 'where'));
    }

    public function validateExists(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'attribute', 'value', 'ignore', 'where'));
    }

    public function validateDescendant(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->__request(__FUNCTION__, compact('modelClass', 'attribute', 'value', 'ignore', 'where'));
    }
}
