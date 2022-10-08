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

use FirecmsExt\Utils\Model\Model;
use Hyperf\Database\Query\Builder;
use Hyperf\Database\Model\Builder as ModelBuilder;

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

    public function model(string $modelClass): Model
    {
        $modelClass = (str_contains($modelClass, '\\App\\Model\\') ? '' : '\\App\\Model\\') . $modelClass;

        return new $modelClass();
    }

    public function validateUnique(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return ! $this->model($modelClass)
            ->where(function ($query) use ($ignore) {
                return $this->ignoreWhere($query, $ignore);
            })
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->where($attribute, $value)
            ->count($attribute);
    }

    public function validateArray(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->model($modelClass)
            ->where(function ($query) use ($ignore) {
                return $this->ignoreWhere($query, $ignore);
            })
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->whereIn($attribute, $value)
            ->count($attribute) === count((array) $value);
    }

    public function validateExists(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return (bool) $this->model($modelClass)
            ->where(function (Builder $query) use ($ignore) {
                return $this->ignoreWhere($query, $ignore);
            })
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->where($attribute, $value)
            ->count($attribute);
    }

    public function validateDescendant(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return (bool) $this->model($modelClass)
            ->where(function ($query) use ($ignore) {
                return $this->ignoreWhere($query, $ignore);
            })
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->first()
            ->descendantWhere()
            ->where($attribute, $value)
            ->count($attribute);
    }

    protected function andWhere(Builder|ModelBuilder $query, array $where): Builder|ModelBuilder
    {
        foreach ($where as $key => $val) {
            if (is_null($val)) {
                $query = $query->whereNull($key);
            } elseif (is_array($val)) {
                $query = $query->whereIn($key, $val);
            } else {
                $query = $query->where($key, $val);
            }
        }
        return $query;
    }

    protected function ignoreWhere(Builder|ModelBuilder $query, array $ignore): Builder|ModelBuilder
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
