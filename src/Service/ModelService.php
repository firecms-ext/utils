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
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Database\Model\Builder;

class ModelService implements ModelServiceInterface
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

    public function create(string $modelClass, array $data): array
    {
        $model = $this->model($modelClass);
        $model->fill($data);
        $model->save();

        return [
            'message' => __('message.Create success'),
        ];
    }

    public function update(string $modelClass, string $id, array $data): array
    {
        $this->model($modelClass)
            ->find($id)
            ->update($data);

        return [
            'message' => __('message.Update success'),
        ];
    }

    public function fillData(string $modelClass, array $attributes, array $parent = null): array
    {
        return $this->model($modelClass)->fillData($attributes, $parent);
    }

    public function batchDataInsert(string $modelClass, array $items, ?array $parent = null): bool
    {
        return $this->model($modelClass)->batchDataInsert($items, $parent);
    }

    #[Cacheable(prefix: 'ModelService', value: '#{id}', ttl: 1)]
    public function find(string $modelClass, string $id, array $with = []): ?array
    {
        return $this->model($modelClass)
            ->with($with)
            ->find($id)
            ?->toArray();
    }

    public function getData(string $modelClass, array $where = [], array $with = []): ?array
    {
        return $this->model($modelClass)
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->with($with)
            ->first()
            ?->toArray();
    }

    public function setData(string $modelClass, array $data, ?array $where = null): ?array
    {
        if ($where) {
            $this->model($modelClass)->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })->update($data);

            return [
                'message' => __('message.Update success'),
            ];
        }
        $model = $this->model($modelClass);
        $model->fill($data);
        $model->save();

        return [
            'message' => __('message.Store success'),
        ];
    }

    public function getTree(string $modelClass, array $where = [], array $columns = ['*'], array $orderBy = ['level' => 'asc', 'sort' => 'asc']): array
    {
        return arrayToTree($this->model($modelClass)
            ->queryParentDescendant($params['parent'] ?? $params['parent_id'] ?? $params['parent_name'] ?? null)
            ->where(function ($query) use ($where) {
                unset($where['parent'], $where['parent_id'], $where['parent_name'],);
                return $this->andWhere($query, $where);
            })
            ->when(count($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $field => $order) {
                    if (is_int($field)) {
                        $query->orderByRaw($order);
                    } else {
                        $query->orderBy($field, $order ?: 'asc');
                    }
                }
            })
            ->selectRaw(implode(',', $columns))
            ->get()
            ->toArray());
    }

    public function getItems(string $modelClass, array $where = [], array $with = [], array $orderBy = []): array
    {
        return $this->model($modelClass)->where(function ($query) use ($where) {
            return $this->andWhere($query, $where);
        })
            ->with($with)
            ->when(count($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $field => $order) {
                    if (is_int($field)) {
                        $query->orderByRaw($order);
                    } else {
                        $query->orderBy($field, $order ?: 'asc');
                    }
                }
            })
            ->get()
            ->toArray();
    }

    public function getList(string $modelClass, array $where = [], array $with = [], int $page = 1, int $limit = 20, array $orderBy = []): array
    {
        $model = $this->model($modelClass);
        $query = $model->where(function ($query) use ($where) {
            return $this->andWhere($query, $where);
        })
            ->with($with);

        $total = $query->count($model->getKeyName() ?: '*');

        if (!$total) {
            return [
                'total' => $total,
                'items' => [],
            ];
        }
        $page = max($page, 1);
        $limit = max($page, 0);
        return [
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'items' => $query->when(count($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $field => $order) {
                    if (is_int($field)) {
                        $query->orderByRaw($order);
                    } else {
                        $query->orderBy($field, $order ?: 'asc');
                    }
                }
            })
                ->when($limit, function ($query) use ($page, $limit) {
                    return $query->offset(($page - 1) * $limit)
                        ->limit($limit);
                })
                ->get()
                ->toArray(),
        ];
    }

    public function validateUnique(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return !$this->model($modelClass)
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
                ->count($attribute) === count((array)$value);
    }

    public function validateExists(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return (bool)$this->model($modelClass)
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
        return (bool)$this->model($modelClass)
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

    protected function model(string $modelClass): Model
    {
        $modelClass = (str_contains($modelClass, 'App\\Model\\') ? '' : 'App\\Model\\') . $modelClass;

        return new $modelClass();
    }

    protected function andWhere(Builder $query, array $where): Builder
    {
        return andWhere($query, $where);
    }

    protected function ignoreWhere(Builder $query, array $ignore): Builder
    {
        return ignoreWhere($query, $ignore);
    }
}
