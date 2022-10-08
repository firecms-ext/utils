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

class ModelService
{
    public function getTableName(string $modelClass, bool $prefix = false): string
    {
        return $this->model($modelClass)->getTableName($prefix);
    }

    public function getTableColumns(string $modelClass): array
    {
        return $this->model($modelClass)->getTableColumns();
    }

    public function getTableFields(string $modelClass): array
    {
        return $this->model($modelClass)->getTableFields();
    }

    public function getPrefix(string $modelClass): string
    {
        return $this->model($modelClass)->getPrefix();
    }

    public function fillData(string $modelClass, array $attributes, array $parent = null): array
    {
        return $this->model($modelClass)->fillData($attributes, $parent);
    }

    public function model(string $modelClass): \App\Model\Model
    {
        $modelClass = (str_contains($modelClass, '\\App\\Model\\') ? '' : '\\App\\Model\\') . $modelClass;

        return new $modelClass();
    }

    public function validateUnique(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return ! $this->model($modelClass)
            ->where(function ($query) use ($ignore) {
                foreach ($ignore as $key => $val) {
                    if ($key && $val) {
                        $query = $query->where($key, '<>', $val);
                    }
                }
            })
            ->where($where)
            ->where($attribute, $value)
            ->count($attribute);
    }

    public function validateArray(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->model($modelClass)
            ->where(function ($query) use ($ignore) {
                foreach ($ignore as $key => $val) {
                    if ($key && $val) {
                        $query = $query->where($key, '<>', $val);
                    }
                }
            })
            ->where($where)
            ->whereIn($attribute, $value)
            ->count($attribute) === count((array) $value);
    }

    public function validateExists(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return (bool) $this->model($modelClass)
            ->where(function ($query) use ($ignore) {
                foreach ($ignore as $key => $val) {
                    if ($key && $val) {
                        $query = $query->where($key, '<>', $val);
                    }
                }
            })
            ->where($where)
            ->where($attribute, $value)
            ->count($attribute);
    }

    public function validateDescendant(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return (bool) $this->model($modelClass)
            ->where(function ($query) use ($ignore) {
                foreach ($ignore as $key => $val) {
                    if ($key && $val) {
                        $query = $query->where($key, '<>', $val);
                    }
                }
            })
            ->where($where)
            ->first()
            ->descendantWhere()
            ->where($attribute, $value)
            ->count($attribute);
    }
}
