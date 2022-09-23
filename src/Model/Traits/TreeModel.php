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
namespace FirecmsExt\Utils\Model\Traits;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Events\Deleted;
use Hyperf\Database\Model\Events\Saved;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Database\Model\Events\Updated;
use Hyperf\Database\Model\Events\Updating;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Collection;

/**
 * @property string $id
 * @property string $parent_id
 * @property int $has_children
 * @property int $level
 * @property string $node
 * @property string $name
 * @property string $code
 * @property string $title
 * @property int $sort
 * @property array $node_ids
 * @property self $parent
 * @property Collection $children
 * @property Collection $ancestors
 * @property Collection $descendantIds
 * @property Collection $descendant_ids
 * @method static static queryParent($value)
 * @method static static queryParentDescendant($value)
 * @method static static queryParentId($value)
 * @method static static queryParentIdDescendant($value)
 * @method static static queryParentName($value)
 * @method static static queryParentNameDescendant($value)
 */
trait TreeModel
{
    /**
     * 上级.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * 子级.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * 上级 name.
     */
    public function getParentNameAttribute(): string
    {
        return (string) $this->parent?->name;
    }

    /**
     * 上级 title.
     */
    public function getParentTitleAttribute(): string
    {
        return (string) $this->parent?->title;
    }

    /**
     * 上级 title.
     */
    public function getParentCodeAttribute(): string
    {
        return (string) $this->parent?->code;
    }

    /**
     * 上级 IDS.
     */
    public function getNodeIdsAttribute(): array
    {
        return array_filter(explode('_', trim($this->node, '_')));
    }

    /**
     * 先祖.
     */
    public function getAncestorsAttribute(): Collection
    {
        return count($this->node_ids) ? self::query()
            ->whereIn('id', $this->node_ids)
            ->orderBy('level')
            ->get() : new Collection();
    }

    /**
     * 完整的 Title（包含先祖）.
     */
    public function getFullTitleAttribute(): string
    {
        return (string) $this->ancestors
            ->pluck('title')
            ->push($this->title)
            ->implode(' / ');
    }

    /**
     * 完整的 Name（包含先祖）.
     */
    public function getFullNameAttribute(): string
    {
        return $this->ancestors
            ->pluck('name')
            ->push($this->title)
            ->implode(' / ');
    }

    /**
     * 数据创建或更新时.
     */
    public function saving(Saving $event): void
    {
        // 设置节点（node）和层级（level）属性
        if ($this->parent_id && $this->parent) {
            $this->level = $this->parent->level + 1;
            $this->node = $this->parent->node . $this->parent_id . '_';
        } else {
            $this->parent_id = null;
            $this->level = 0;
            $this->node = '_';
        }
    }

    /**
     * 数据创建或更新后.
     */
    public function saved(Saved $event): void
    {
        // 更新 上级 has_children 属性
        if ($this->parent_id && ! $this->parent->has_children) {
            $this->parent->has_children = true;
            $this->parent->save();
        }
    }

    /**
     * 更新之前.
     */
    public function updating(Updating $event): void
    {
        Db::beginTransaction();
        if ($this->getOriginal('parent_id') && $this->getOriginal('parent_id') !== $this->parent_id) {
            if (! self::where('parent_id', $this->getOriginal('parent_id'))->where('id', '<>', $this->id)->count('id')) {
                self::where('id', $this->getOriginal('parent_id'))->update(['has_children' => false]);
            }
        }
    }

    /**
     * 更新之后.
     */
    public function updated(Updated $event): void
    {
        if ($this->has_children) {
            // 更新子集节点
            foreach ($this->children as $child) {
                // 节点变更后更新子节点
                if ($child->node !== $this->node . $this->id . '_') {
                    $child->save();
                }
            }
        }
        // 提交 事务（报错就回滚）
        try {
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();
            throw $e;
        }
    }

    /**
     * 数据删除后.
     */
    public function deleted(Deleted $event): void
    {
        if ($this->has_children) {
            // 删除后代（子孙）（移入回收站）
            $this->descendantWhere()
                ->delete();
        }
    }

    /**
     * 是否是后代（子孙）.
     */
    public function isDescendant(string $id): bool
    {
        return (bool) $this->descendantWhere()
            ->where('id', $id)
            ->count('id');
    }

    /**
     * 后代（子孙）的id.
     */
    public function getDescendantIdsAttribute(): Collection
    {
        return $this->descendantWhere()
            ->pluck('id');
    }

    /**
     * 根据上级 ID|Name 查询子集.
     */
    public function scopeQueryParent(Builder $query, ?string $value): Builder
    {
        if (is_numeric($value)) {
            return $query->queryParentId($value);
        }
        return $query->queryParentName($value);
    }

    /**
     * 根据上级 ID|Name 查询后代（子孙）.
     */
    public function scopeQueryParentDescendant(Builder $query, ?string $value): Builder
    {
        if (is_numeric($value)) {
            return $query->queryParentIdDescendant($value);
        }
        return $query->queryParentNameDescendant($value);
    }

    /**
     * 根据上级 ID 查询子集.
     */
    public function scopeQueryParentId(Builder $query, ?string $value): Builder
    {
        return $query->when(is_numeric($value), function ($query) use ($value) {
            return $query->where('parent_id', $value);
        });
    }

    /**
     * 根据上级 Name 查询子集.
     */
    public function scopeQueryParentName(Builder $query, ?string $value): Builder
    {
        return $query->when($value, function ($query, $value) {
            $model = self::where('name', $value)->first();
            return $query->where('parent_id', $model ? $model->id : -1);
        });
    }

    /**
     * 根据上级 ID 查询后代（子孙）.
     */
    public function scopeQueryParentIdDescendant(Builder $query, ?string $value): Builder
    {
        return $query->when(is_numeric($value), function ($query) use ($value) {
            return $query->where('node', 'like', self::find($value)->node . $value . '_%');
        });
    }

    /**
     * 根据上级 Name 查询后代（子孙）.
     */
    public function scopeQueryParentNameDescendant(Builder $query, ?string $value): Builder
    {
        return $query->when($value, function ($query) use ($value) {
            $model = self::where('name', $value)->first();
            return $query->where('node', 'like', $model->node . $model->id . '_%');
        });
    }

    /**
     * 后代（子孙）的查询条件.
     */
    protected function descendantWhere(): Builder
    {
        return self::where('node', 'like', $this->node . $this->id . '_%');
    }
}
