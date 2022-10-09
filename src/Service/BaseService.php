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

class BaseService
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

        return json_decode(
            $this->getCollection(
                $model->with($this->listWith())
                    ->where(function ($query) use ($params) {
                        return $this->baseWhere($query, $params);
                    })
                    ->where(function ($query) use ($params) {
                        return $this->listWhere($query, $params);
                    })
                    ->when(
                        (string) ($params['field'] ?? null) ?: ($this->orderField ?: $model->getKeyName()),
                        function ($query, $value) use ($params) {
                            // 排序方式
                            return $query->orderBy($value, in_array(
                                ($params['order'] ?? null) ?: $this->orderBy,
                                ['descend', 'desc']
                            ) ? 'desc' : 'asc');
                        }
                    )
                    ->when((bool) ($params['recycle'] ?? null), function ($query) {
                        // 回收站
                        return $query->onlyTrashed();
                    })
                    ->paginate((int) ($params['perpage'] ?? $params['pageSize']) ?? 20)
            )
                ->toResponse()
                ->getBody()
                ->getContents(),
            true
        );
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
                ->when(
                    (string) ($params['field'] ?? null) ?: ($this->orderField ?: $model->getKeyName()),
                    function ($query, $value) use ($params) {
                        // 排序方式
                        return $query->orderBy($value, in_array(
                            ($params['order'] ?? null) ?: $this->orderBy,
                            ['descend', 'desc']
                        ) ? 'desc' : 'asc');
                    }
                )
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
                ->where(function (Builder $query) use ($params) {
                    return $this->baseWhere($query, $params);
                })
                ->where(function (Builder $query) use ($params) {
                    return $this->listWhere($query, $params);
                })
                ->when($sort, function ($query, $value) {
                    foreach ($value as $field => $order) {
                        $query = $query->orderBy($field, $order);
                    }
                    return $query;
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
        return treeToOptions(toTree(
            $this->getModelInstance()
                ->where(function (Builder $query) use ($params) {
                    return $this->baseWhere($query, $params);
                })
                ->where(function (Builder $query) use ($params) {
                    return $this->treeWhere($query, $params);
                })
                ->orderBy('level')
                ->when($sort, function ($query, $value) {
                    foreach ($value as $field => $order) {
                        $query = $query->orderBy($field, $order);
                    }
                    return $query;
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
        $model = $this->getModelInstance()->create($this->storeData($params));
        // 新增之后
        $this->afterStore($model, $params);

        return $this->getResource($model)->toArray();
    }

    /**
     * 详情.
     */
    public function show(string $id): array
    {
        return $this->getResource(
            $this->getModelInstance()->findOrFail($id)
        )->toArray();
    }

    /**
     * 更新.
     */
    public function update(array $params, string $id): array
    {
        // 查询数据
        $model = $this->getModelInstance()->findOrFail($id);
        // 更新数据
        $model->update($this->updateData($params));
        // 更新之后
        $this->afterUpdate($model, $params);

        return ['message' => __('message.Update success')];
    }

    /**
     * 软删除.
     */
    public function destroy(string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, &$count) {
            $count = $this->queryByIds($ids, false, true)
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
    public function forceDestroy(string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, &$count) {
            $model = $this->getModelInstance();
            $count = $model->onlyTrashed()
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
    public function restore(string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, &$count) {
            $model = $this->getModelInstance();
            $count = $model->onlyTrashed()
                ->whereIn($model->getKeyName(), $this->getParseIds($ids))
                ->restore();
        });

        return [
            'count' => $count,
            'message' => __('message.Restore success', compact('count')),
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
    public function clearEmpty(): array
    {
        $count = 0;
        Db::transaction(function () use (&$count) {
            $count = $this->getModelInstance()
                ->query(true)
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
    public function forceClearEmpty(): array
    {
        $count = 0;
        Db::transaction(function () use (&$count) {
            $count = $this->getModelInstance()
                ->onlyTrashed()
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
    public function top(array $params, string $id): array
    {
        $model = $this->getModelInstance()->findOrFail($id);
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
    public function sort(array $params, string $id): array
    {
        $model = $this->getModelInstance()->findOrFail($id);
        Db::transaction(function () use ($params, $model) {
            $model->sort = $params['sort'];
            $model->save();
        });

        return [
            'message' => __('message.Sort success'),
        ];
    }

    /**
     * 显示|隐藏（批量）.
     */
    public function display(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            $count = $this->queryByIds($ids, false, true)->update([
                'display' => $params['display'],
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
    public function enable(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            $count = $this->queryByIds($ids, false, true)
                ->update([
                    'enable' => $params['enable'],
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
    public function recommend(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            $count = $this->queryByIds($ids, false, true)
                ->update([
                    'recommend' => $params['recommend'],
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
     *  异常/正常（批量）.
     */
    public function unusual(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            $count = $this->queryByIds($ids, false, true)
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
    public function hot(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            $count = $this->queryByIds($ids, false, true)
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
    public function directly(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            $count = $this->queryByIds($ids, false, true)
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
    public function publish(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            if ($params['publish']) {
                $count = $this->queryByIds($ids, false, true)
                    ->update([
                        'publish' => true,
                        'publish_at' => $params['publish_at'] ?: Carbon::now(),
                    ]);
            } else {
                $count = $this->queryByIds($ids, false, true)
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
    public function publishExpired(array $params, string $ids): array
    {
        $count = 0;
        Db::transaction(function () use ($ids, $params, &$count) {
            if ($params['publish']) {
                $count = $this->queryByIds($ids, false, true)
                    ->update([
                        'publish' => true,
                        'publish_at' => $params['publish_at'] ?: Carbon::now(),
                        'expired_at' => $params['expired_at'] ?: null,
                    ]);
            } else {
                $count = $this->queryByIds($ids, false, true)
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
     * 基础查询条件.
     */
    protected function baseWhere(Builder $query, array $params): Builder
    {
        return $query->when(is_numeric($params['display'] ?? null), function ($query) use ($params) {
            // 是否显示
            return $query->where('display', (bool) $params['display']);
        })->when(is_numeric($params['unusual'] ?? null), function ($query) use ($params) {
            // 是否异常
            return $query->where('unusual', (bool) $params['unusual']);
        })->when(is_numeric($params['draft'] ?? null), function ($query) use ($params) {
            // 是否草稿
            return $query->where('draft', (bool) $params['draft']);
        })->when(is_numeric($params['publish'] ?? null), function ($query) use ($params) {
            // 是否发布
            return $query->where('publish', (bool) $params['publish']);
        })->when(is_numeric($params['top'] ?? null), function ($query) use ($params) {
            // 是否置顶
            return $query->where('top', (bool) $params['top']);
        })->when(is_numeric($params['recommend'] ?? null), function ($query) use ($params) {
            // 是否推荐
            return $query->where('recommend', (bool) $params['recommend']);
        })->when(is_numeric($params['enable'] ?? null), function ($query) use ($params) {
            // 是否启用
            return $query->where('enable', (bool) $params['enable']);
        })->queryKeyword($params['keyword'] ?? null);
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
        return toTree((new $this->treeCollectionClass($collection))->toArray());
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
    protected function afterStore(mixed $model, array $params): void
    {
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
    protected function afterUpdate(mixed $model, array $params): void
    {
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
    protected function queryByIds(string $ids, bool $recycle = false, bool $cache = false): Builder
    {
        $ids = $this->getParseIds($ids);

        $model = $this->getModelInstance();
        if ($recycle) {
            $model = $model->onlyTrashed();
        }
        return $model->query($cache)->whereIn($model->getKeyName(), $ids);
    }
}
