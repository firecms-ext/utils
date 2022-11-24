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

use Carbon\Carbon;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Resource\Json\JsonResource;
use Hyperf\Resource\Json\ResourceCollection;
use Hyperf\Utils\Collection;

class BaseService implements BaseServiceInterface
{
    /** @var string 模型类名 */
    protected string $modelClass;

    /** @var null|string 响应资源类 */
    protected ?string $showResourceClass;

    /** @var null|string 响应集合资源 */
    protected ?string $listCollectionClass;

    /** @var null|string 树响应集合资源 */
    protected ?string $treeCollectionClass;

    /** @var string 排序字段 */
    protected string $orderField = '';

    /** @var string 排序方式（desc|asc） */
    protected string $orderBy = 'desc';

    /**
     * 分页.
     */
    public function listTable(array $params): array
    {
        $model = $this->getModelInstance();
        $query = $model->with($this->listWith())
            ->where(function ($query) use ($params) {
                return $this->baseWhere($query, $params);
            })
            ->where(function ($query) use ($params) {
                return $this->listWhere($query, $params);
            })
            ->when((bool) ($params['recycle'] ?? null), function ($query) {
                // 回收站
                return $query->onlyTrashed();
            });
        $total = $query->count($model->getKeyName() ?: '*');
        if (! $total) {
            return [
                'total' => $total,
                'items' => [],
            ];
        }

        $page = $this->getPage($params);
        $limit = $this->getLimit($params);

        return [
            'total' => $total,
            'items' => $this->getCollection($query->when(true, function ($query) use ($params) {
                return $this->getOrderBy($query, $params, [$this->orderField => $this->orderBy]);
            })
                ->when($limit, function ($query) use ($page, $limit) {
                    return $query->offset(($page - 1) * $limit)
                        ->limit($limit);
                })
                ->selectRaw(implode(',', $this->listTableColumns($params)))
                ->get())
                ->toArray(),
        ];
    }

    /**
     * 树形.
     */
    public function treeTable(array $params): array
    {
        $model = $this->getModelInstance();
        return $this->getTreeCollection(
            $model->with($this->treeWith())
                ->where(function ($query) use ($params) {
                    return $this->baseWhere($query, $params);
                })
                ->where(function ($query) use ($params) {
                    return $this->treeWhere($query, $params);
                })
                ->orderBy('level')
                ->when(true, function ($query) use ($params) {
                    return $this->getOrderBy($query, $params, ['sort' => 'asc']);
                })
                ->selectRaw(implode(',', $this->treeTableColumns($params)))
                ->get()
        );
    }

    /**
     * 选项.
     */
    public function options(array $params, ?array $columns = ['id', 'title', 'enable'], ?array $sort = ['sort' => 'asc']): array
    {
        return options(
            $this->getModelInstance()
                ->where(function ($query) use ($params) {
                    return $this->baseWhere($query, $params);
                })
                ->where(function ($query) use ($params) {
                    return $this->listWhere($query, $params);
                })
                ->when(true, function ($query) use ($params, $sort) {
                    return $this->getOrderBy($query, $params, (array) $sort);
                })
                ->selectRaw(implode(',', $columns))
                ->get()
                ->toArray(),
            'title',
            'id'
        );
    }

    /**
     * 选项（Tree）.
     */
    public function treeOptions(array $params, ?array $columns = ['id', 'parent_id', 'title', 'enable'], ?array $sort = ['sort' => 'asc']): array
    {
        return treeToOptions(arrayToTree(
            $this->getModelInstance()
                ->where(function ($query) use ($params) {
                    return $this->baseWhere($query, $params);
                })
                ->where(function ($query) use ($params) {
                    return $this->treeWhere($query, $params);
                })
                ->orderBy('level')
                ->when(true, function ($query) use ($params, $sort) {
                    return $this->getOrderBy($query, $params, (array) $sort);
                })
                ->selectRaw(implode(',', $columns))
                ->get()
                ->toArray(),
            'parent_id',
            'id',
            'children'
        ), 'title', 'id', 'children');
    }

    /**
     * 新增.
     */
    public function store(array $params): array
    {
        // 新增数据
        $model = $this->getModelInstance()
            ->create($this->storeData($params));
        // 新增之后
        if ($result = $this->afterStore($model, $params)) {
            return $result;
        }

        return $this->getResource($model)
            ->toArray();
    }

    /**
     * 详情.
     */
    public function show(string $id, array $where = []): array
    {
        return $this->getResource(
            $this->getModelInstance()
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->findOrFail($id)
        )
            ->toArray();
    }

    /**
     * 更新.
     */
    public function update(array $params, string $id, array $where = []): array
    {
        // 查询数据
        $model = $this->getModelInstance()
            ->when(count($where), function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->findOrFail($id);
        // 更新数据
        $model->update($this->updateData($params));
        // 更新之后
        if ($result = $this->afterUpdate($model, $params)) {
            return $result;
        }

        return ['message' => __('message.Update success')];
    }

    /**
     * 软删除.
     */
    public function destroy(string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->delete();
        });

        return [
            'count' => $count,
            'message' => __('message.Destroy success', compact('count')),
        ];
    }

    /**
     * 硬删除.
     */
    public function forceDestroy(string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $where, &$count) {
            $model = $this->getModelInstance();
            $count = $model->onlyTrashed()
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->whereIn($model->getKeyName(), $this->getParseIds($ids))
                ->forceDelete();
        });

        return [
            'count' => $count,
            'message' => __('message.Destroy success', compact('count')),
        ];
    }

    /**
     * 恢复.
     */
    public function restore(string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $where, &$count) {
            $model = $this->getModelInstance();
            $count = $model->onlyTrashed()
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->whereIn($model->getKeyName(), $this->getParseIds($ids))
                ->restore();
        });

        return [
            'count' => $count,
            'message' => __('message.Restore success', compact('count')),
        ];
    }

    /**
     * 已读.
     */
    public function read(array $params): array
    {
        $count = 0;
        Db::transaction(function () use ($params, &$count) {
            $count = $this->getModelInstance()
                ->where('read', false)
                ->where(function ($query) use ($params) {
                    return $this->andWhere($query, $params);
                })
                ->update([
                    'read' => true,
                    'read_at' => Carbon::now(),
                ]);
        });

        return [
            'message' => __('message.Read success', compact('count')),
        ];
    }

    /**
     * 清理.
     */
    public function clear(array $params): array
    {
        $count = 0;
        Db::transaction(function () use ($params, &$count) {
            $count = $this->getModelInstance()
                ->query(true)
                ->when((string) $params['start_at'], function ($query, $value) {
                    return $query->where('created_at', '>=', $value);
                })
                ->where('created_at', '<=', (string) $params['end_at'])
                ->where(function ($query) use ($params) {
                    unset($params['start_at'], $params['end_at']);

                    return $this->andWhere($query, $params);
                })
                ->delete();
        });

        return [
            'count' => $count,
            'message' => __('message.Clear success', compact('count')),
        ];
    }

    /**
     * 彻底清理.
     */
    public function forceClear(array $params): array
    {
        $count = 0;
        Db::transaction(function () use ($params, &$count) {
            $count = $this->getModelInstance()
                ->when((string) $params['start_at'], function ($query, $value) {
                    return $query->where('created_at', '>=', $value);
                })
                ->where('created_at', '<=', (string) $params['end_at'])
                ->where(function ($query) use ($params) {
                    unset($params['start_at'], $params['end_at']);

                    return $this->andWhere($query, $params);
                })
                ->onlyTrashed()
                ->forceDelete();
        });

        return [
            'count' => $count,
            'message' => __('message.Clear success', compact('count')),
        ];
    }

    /**
     * 清空数据.
     */
    public function clearEmpty(?array $params = []): array
    {
        $count = 0;
        Db::transaction(function () use (&$count, $params) {
            $count = $this->getModelInstance()
                ->query(true)
                ->where(function ($query) use ($params) {
                    return $this->andWhere($query, $params);
                })
                ->delete();
        });

        return [
            'count' => $count,
            'message' => __('message.Clear empty success', compact('count')),
        ];
    }

    /**
     * 清空.
     */
    public function forceClearEmpty(?array $params = []): array
    {
        $count = 0;
        Db::transaction(function () use (&$count, $params) {
            $count = $this->getModelInstance()
                ->onlyTrashed()
                ->where(function ($query) use ($params) {
                    return $this->andWhere($query, $params);
                })
                ->forceDelete();
        });

        return [
            'count' => $count,
            'message' => __('message.Clear empty success', compact('count')),
        ];
    }

    /**
     * 置顶|取消.
     */
    public function top(array $params, string $id, array $where = []): array
    {
        $model = $this->getModelInstance()
            ->when(count($where), function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->findOrFail($id);

        Db::transaction(function () use ($model, $params, $id) {
            if ($params['top']) {
                $this->getModelInstance()
                    ->query(true)
                    ->where($model->getKeyName(), '<>', $id)
                    ->update(['top' => false]);
                $model->top = true;
            } else {
                $model->top = false;
            }
            $model->save();
        });

        return [
            'message' => $params['top'] ?
                __('message.Top success') : __('message.Top cancel'),
        ];
    }

    /**
     * 排序.
     */
    public function sort(array $params, string $id, array $where = []): array
    {
        $model = $this->getModelInstance()
            ->when(count($where), function ($query) use ($where) {
                return $this->andWhere($query, $where);
            })
            ->findOrFail($id);
        Db::transaction(function () use ($params, $model) {
            $model->sort = (int) $params['sort'];
            $model->save();
        });

        return [
            'message' => __('message.Sort success'),
        ];
    }

    /**
     * 显示|隐藏（批量）.
     */
    public function display(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update([
                    'display' => (int) $params['display'],
                ]);
        });

        return [
            'count' => $count,
            'message' => $params['display'] ?
                __('message.Display success', compact('count')) :
                __('message.Hide success', compact('count')),
        ];
    }

    /**
     * 启用|禁用（批量）.
     */
    public function enable(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update([
                    'enable' => (int) $params['enable'],
                ]);
        });

        return [
            'count' => $count,
            'message' => $params['enable'] ?
                __('message.Enable success', compact('count')) :
                __('message.Disable success', compact('count')),
        ];
    }

    /**
     * 推荐（批量）.
     */
    public function recommend(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update([
                    'recommend' => (int) $params['recommend'],
                ]);
        });

        return [
            'count' => $count,
            'message' => $params['recommend'] ?
                __('message.Recommend success', compact('count')) :
                __('message.Recommend cancel', compact('count')),
        ];
    }

    /**
     * 状态（批量）.
     */
    public function state(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update([
                    'state' => (int) $params['state'],
                ]);
        });

        return [
            'count' => $count,
            'message' => __('message.State success', compact('count')),
        ];
    }

    /**
     *  异常/正常（批量）.
     */
    public function unusual(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update([
                    'unusual' => $params['unusual'],
                ]);
        });

        return [
            'count' => $count,
            'message' => $params['unusual'] ?
                __('message.Unusual success', compact('count')) :
                __('message.Unusual cancel', compact('count')),
        ];
    }

    /**
     *  热门/取消（批量）.
     */
    public function hot(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update([
                    'hot' => $params['hot'],
                ]);
        });

        return [
            'count' => $count,
            'message' => $params['hot'] ?
                __('message.Hot success', compact('count')) :
                __('message.Hot cancel', compact('count')),
        ];
    }

    /**
     *  直接/取消（批量）.
     */
    public function directly(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            $count = $this->queryByIds($ids, false)
                ->when(count($where), function ($query) use ($where) {
                    return $this->andWhere($query, $where);
                })
                ->update([
                    'directly' => $params['directly'],
                ]);
        });

        return [
            'count' => $count,
            'message' => $params['directly'] ?
                __('message.Directly success', compact('count')) :
                __('message.Directly cancel', compact('count')),
        ];
    }

    /**
     * 发布|取消（批量）.
     */
    public function publish(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            if ($params['publish']) {
                $count = $this->queryByIds($ids, false)
                    ->when(count($where), function ($query) use ($where) {
                        return $this->andWhere($query, $where);
                    })
                    ->update([
                        'publish' => true,
                        'publish_at' => isset($params['publish_at']) && $params['publish_at'] ?: Carbon::now(),
                    ]);
            } else {
                $count = $this->queryByIds($ids, false)
                    ->when(count($where), function ($query) use ($where) {
                        return $this->andWhere($query, $where);
                    })
                    ->update([
                        'publish' => false,
                        'publish_at' => null,
                    ]);
            }
        });

        return [
            'count' => $count,
            'message' => $params['publish'] ?
                __('message.Publish success', compact('count')) :
                __('message.Publish cancel', compact('count')),
        ];
    }

    /**
     * 发布|取消（批量）（含有效期）.
     */
    public function publishExpired(array $params, string $ids, array $where = []): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, $where, &$count) {
            if ($params['publish']) {
                $count = $this->queryByIds($ids, false)
                    ->when(count($where), function ($query) use ($where) {
                        return $this->andWhere($query, $where);
                    })
                    ->update([
                        'publish' => true,
                        'publish_at' => isset($params['publish_at']) && $params['publish_at'] ?: Carbon::now(),
                        'expired_at' => $params['expired_at'] ?? null,
                    ]);
            } else {
                $count = $this->queryByIds($ids, false)
                    ->when(count($where), function ($query) use ($where) {
                        return $this->andWhere($query, $where);
                    })
                    ->update([
                        'publish' => false,
                        'publish_at' => null,
                        'expired_at' => null,
                    ]);
            }
        });

        return [
            'count' => $count,
            'message' => $params['publish'] ?
                __('message.Publish success', compact('count')) :
                __('message.Publish cancel', compact('count')),
        ];
    }

    /**
     * 获取排序规则.
     */
    protected function getOrderBy(Builder $query, array $params, array $orderBy = []): Builder
    {
        if (! empty($params['orderBy'])) {
            $orderBy = $params['orderBy'];
        } elseif (! empty($params['field']) && ! empty($params['order'])) {
            $orderBy = [$params['field'] => in_array($params['order'], ['descend', 'desc']) ? 'desc' : 'asc'];
        }

        foreach ($orderBy as $field => $order) {
            if (is_int($field) && $order) {
                $query = $query->orderByRaw($order);
            } elseif (is_string($field) && $field) {
                $query = $query->orderBy($field, $order);
            }
        }

        return $query;
    }

    /**
     * 解析页码
     */
    protected function getPage(array $params, int $default = 1): int
    {
        return (int) max($params['page'] ?? $default, 1);
    }

    /**
     * 解析页容.
     */
    protected function getLimit(array $params, int $default = 10): int
    {
        return (int) max($params['limit'] ?? $params['perpage'] ?? $params['pageSize'] ?? $default, 0);
    }

    /**
     * 基础查询条件.
     */
    protected function baseWhere(Builder $query, array $params): Builder
    {
        return $query->when(is_numeric($params['read'] ?? null), function ($query) use ($params) {
            // 是否已读
            return $query->where('read', (int) $params['read']);
        })
            ->when(is_numeric($params['display'] ?? null), function ($query) use ($params) {
                // 是否显示
                return $query->where('display', (int) $params['display']);
            })
            ->when(is_numeric($params['unusual'] ?? null), function ($query) use ($params) {
                // 是否异常
                return $query->where('unusual', (int) $params['unusual']);
            })
            ->when(is_numeric($params['repeal'] ?? null), function ($query) use ($params) {
                // 是否撤销
                return $query->where('repeal', (int) $params['repeal']);
            })
            ->when(is_numeric($params['draft'] ?? null), function ($query) use ($params) {
                // 是否草稿
                return $query->where('draft', (int) $params['draft']);
            })
            ->when(is_numeric($params['state'] ?? null), function ($query) use ($params) {
                // 当前状态
                return $query->where('state', (int) $params['state']);
            })
            ->when(is_numeric($params['publish'] ?? null), function ($query) use ($params) {
                // 是否发布
                return $query->where('publish', (int) $params['publish']);
            })
            ->when(is_numeric($params['top'] ?? null), function ($query) use ($params) {
                // 是否置顶
                return $query->where('top', (int) $params['top']);
            })
            ->when(is_numeric($params['hot'] ?? null), function ($query) use ($params) {
                // 是否热门
                return $query->where('hot', (int) $params['hot']);
            })
            ->when(is_numeric($params['enable'] ?? null), function ($query) use ($params) {
                // 是否启用
                return $query->where('enable', (int) $params['enable']);
            })
            ->when(is_numeric($params['directly'] ?? null), function ($query) use ($params) {
                // 是否直接
                return $query->where('directly', (int) $params['directly']);
            })
            ->when(is_numeric($params['recommend'] ?? null), function ($query) use ($params) {
                // 是否推荐
                return $query->where('recommend', (int) $params['recommend']);
            })
            ->queryKeyword($params['keyword'] ?? '');
    }

    /**
     * 其他查询条件.
     */
    protected function andWhere(Builder $query, ?array $params): Builder
    {
        return $query->when($params, function ($query) use ($params) {
            return andWhere($query, $params);
        });
    }

    /**
     * 分页列表查询（预加载）.
     */
    protected function listWith(): array
    {
        return [];
    }

    /**
     * 分页列表查询条件.
     */
    protected function listWhere(Builder $query, array $params): Builder
    {
        return $query;
    }

    /**
     * 分页列表查询字段。
     */
    protected function listTableColumns(array $params): array
    {
        return $params['columns'] ?? ['*'];
    }

    /**
     * 树列表查询（预加载）.
     */
    protected function treeWith(): array
    {
        return [];
    }

    /**
     * 树列表查询条件.
     */
    protected function treeWhere(Builder $query, array $params): Builder
    {
        return $query;
    }

    /**
     * 树列表查询字段.
     */
    protected function treeTableColumns(array $params): array
    {
        return $params['columns'] ?? ['*'];
    }

    /**
     * 获取模型实例.
     */
    protected function getModelInstance(): Model
    {
        return new $this->modelClass();
    }

    /**
     * 获取响应集合.
     */
    protected function getCollection(mixed $collection): ResourceCollection
    {
        return new $this->listCollectionClass($collection);
    }

    /**
     * 获取树响应集合.
     */
    protected function getTreeCollection(Collection $collection): array
    {
        return arrayToTree((new $this->treeCollectionClass($collection))->toArray());
    }

    /**
     * 新增数据.
     */
    protected function storeData(array $params): array
    {
        return $params;
    }

    /**
     * 新增之后.
     */
    protected function afterStore(mixed $model, array $params): mixed
    {
        return null;
    }

    /**
     * 更新数据.
     */
    protected function updateData(array $params): array
    {
        return $params;
    }

    /**
     * 更新之后.
     */
    protected function afterUpdate(mixed $model, array $params): mixed
    {
        return null;
    }

    /**
     * 获取响应资源.
     */
    protected function getResource(mixed $model, ?string $resourceClass = null): JsonResource
    {
        return $resourceClass ? new $resourceClass($model) : new $this->showResourceClass($model);
    }

    /**
     * 获取解析 ids.
     */
    protected function getParseIds(string $ids): array
    {
        $items = [];
        foreach (explode(',', $ids) as $id) {
            if ($id > 0) {
                $items[$id] = $id;
            }
        }

        return array_values($items);
    }

    /**
     * Ids 查询条件.
     */
    protected function queryByIds(string $ids, bool $recycle = false): Builder
    {
        $ids = $this->getParseIds($ids);

        $model = $this->getModelInstance();
        if ($recycle) {
            $model = $model->onlyTrashed();
        }
        return $model->query(true)
            ->whereIn($model->getKeyName(), $ids);
    }
}
