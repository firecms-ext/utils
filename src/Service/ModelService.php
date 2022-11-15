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
    /**
     * 获取数据库 表名.
     */
    public function getTableName(string $modelClass, bool $prefix = false): string
    {
        return $this->getModelInstance($modelClass)->getTableName($prefix);
    }

    /**
     * 获取数据库表 列信息.
     */
    public function getTableColumns(string $modelClass): array
    {
        return $this->getModelInstance($modelClass)->getTableColumns();
    }

    /**
     * 获取数据库表 字段.
     */
    public function getTableFields(string $modelClass): array
    {
        return $this->getModelInstance($modelClass)->getTableFields();
    }

    /**
     * 获取数据库表 前缀。
     */
    public function getPrefix(string $modelClass): string
    {
        return $this->getModelInstance($modelClass)->getPrefix();
    }

    /**
     * 新增数据.
     */
    public function create(string $modelClass, array $data): array
    {
        $model = $this->getModelInstance($modelClass);
        $model->fill($data);
        $model->save();

        return [
            'message' => __('message.Create success'),
        ];
    }

    /**
     * 更新数据.
     */
    public function update(string $modelClass, string $id, array $data): array
    {
        $this->getModelInstance($modelClass)
            ->find($id)
            ->update($data);

        return [
            'message' => __('message.Update success'),
        ];
    }

    /**
     * 模型填充数据.
     */
    public function fillData(string $modelClass, array $attributes, array $parent = null): array
    {
        return $this->getModelInstance($modelClass)->fillData($attributes, $parent);
    }

    /**
     * 批量插入数据。
     */
    public function batchDataInsert(string $modelClass, array $items, ?array $parent = null): bool
    {
        return $this->getModelInstance($modelClass)->batchDataInsert($items, $parent);
    }

    /**
     * 模型查询（主键）.
     */
    #[Cacheable(prefix: 'ModelService', value: '#{id}', ttl: 1)]
    public function find(string $modelClass, string $id, array $with = []): ?array
    {
        return $this->getModelInstance($modelClass)
            ->with($with)
            ->find($id)
            ?->toArray();
    }

    /**
     * 模型查询（自定义条件）.
     */
    public function getData(string $modelClass, array $where = [], array $with = []): ?array
    {
        return $this->getModelInstance($modelClass)
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->with($with)
            ->first()
            ?->toArray();
    }

    /**
     * 新增（或批量更新）.
     */
    public function setData(string $modelClass, array $data, ?array $where = null): ?array
    {
        if ($where) {
            $this->getModelInstance($modelClass)
                ->where(function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update($data);

            return [
                'message' => __('message.Update success'),
            ];
        }
        $model = $this->getModelInstance($modelClass);
        $model->fill($data);
        $model->save();

        return [
            'message' => __('message.Store success'),
        ];
    }

    /**
     * 获取数据集合.
     */
    public function getItems(string $modelClass, array $where = [], array $with = [], array $orderBy = []): array
    {
        return $this->getModelInstance($modelClass)->where(function ($query) use ($where) {
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

    /**
     * 获取分页列表.
     */
    public function getList(string $modelClass, array $where = [], array $with = [], int $page = 1, int $limit = 20, array $orderBy = []): array
    {
        $model = $this->getModelInstance($modelClass);
        $query = $model->where(function ($query) use ($where) {
            return $this->andWhere($query, $where);
        })
            ->with($with);

        $total = $query->count($model->getKeyName() ?: '*');

        if (! $total) {
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

    /**
     * 获取 Tree 集合.
     */
    public function getTree(string $modelClass, array $where = [], array $columns = ['*'], array $orderBy = ['level' => 'asc', 'sort' => 'asc']): array
    {
        return arrayToTree($this->getModelInstance($modelClass)
            ->queryParentDescendant($params['parent'] ?? $params['parent_id'] ?? $params['parent_name'] ?? null)
            ->where(function ($query) use ($where) {
                unset($where['parent'], $where['parent_id'], $where['parent_name']);
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
            ->selectRaw(implode(',', array_filter($columns)))
            ->get()
            ->toArray(), 'parent_id', 'id', 'children');
    }

    /**
     * 验证唯一性.
     */
    public function validateUnique(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return ! $this->getModelInstance($modelClass)
            ->where(function ($query) use ($ignore) {
                return $this->ignoreWhere($query, $ignore);
            })
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->where($attribute, $value)
            ->count($attribute);
    }

    /**
     * 验证数组有效.
     */
    public function validateArray(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return $this->getModelInstance($modelClass)
            ->where(function ($query) use ($ignore) {
                return $this->ignoreWhere($query, $ignore);
            })
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->whereIn($attribute, $value)
            ->count($attribute) === count((array) $value);
    }

    /**
     * 验证是否存在.
     */
    public function validateExists(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return (bool) $this->getModelInstance($modelClass)
            ->where(function (Builder $query) use ($ignore) {
                return $this->ignoreWhere($query, $ignore);
            })
            ->where(function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->where($attribute, $value)
            ->count($attribute);
    }

    /**
     * 验证是否子孙.
     */
    public function validateDescendant(string $modelClass, string $attribute, mixed $value, array $ignore = [], array $where = []): bool
    {
        return (bool) $this->getModelInstance($modelClass)
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

    protected function getModelInstance(string $modelClass): Model
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
