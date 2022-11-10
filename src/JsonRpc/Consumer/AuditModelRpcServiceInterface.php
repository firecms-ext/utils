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

interface AuditModelRpcServiceInterface
{
    public function getTableName(string $modelClass, bool $prefix = false): string;

    public function getTableColumns(string $modelClass): array;

    public function getTableFields(string $modelClass): array;

    public function getPrefix(string $modelClass): string;

    public function create(string $modelClass, array $data): array;

    public function update(string $modelClass, string $id, array $data): array;

    public function fillData(string $modelClass, array $attributes, array $parent = null): array;

    public function batchDataInsert(string $modelClass, array $items, ?array $parent = null): bool;

    public function find(string $modelClass, string $id, array $with = []): ?array;

    public function getData(string $modelClass, array $where = [], array $with = []): ?array;

    public function setData(string $modelClass, array $data, ?array $where = null): ?array;

    public function getItems(string $modelClass, array $where = [], array $with = [], int $page = 1, int $limit = 20, string|array $orderBy = null): array;

    public function validateUnique(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool;

    public function validateArray(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool;

    public function validateExists(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool;

    public function validateDescendant(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool;
}
