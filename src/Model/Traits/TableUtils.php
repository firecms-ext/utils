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

use Carbon\Carbon;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;

trait TableUtils
{
    /**
     * 获取数据库 表名.
     */
    public static function getTableName(bool $prefix = false): string
    {
        return ($prefix ? static::getPrefix() : '') . static::getInstance()->getTable();
    }

    /**
     * 获取数据库表 列信息.
     */
    public static function getTableColumns(): array
    {
        if (!$items = cache()->get(static::class . __FUNCTION__)) {
            foreach (Db::select('SHOW COLUMNS FROM ' . static::getTableName(true)) as $row) {
                $items[$row->Field] = $row;
            }
            cache()->set(static::class . __FUNCTION__, $items, 1);
        }

        return $items;
    }

    /**
     * 获取数据库表 字段.
     */
    public static function getTableFields(): array
    {
        return array_keys(static::getTableColumns());
    }

    /**
     * 获取数据库表 前缀。
     */
    public static function getPrefix(): string
    {
        return (string)static::query()->getConnection()->getTablePrefix();
    }

    /**
     * 是否包含字段.
     */
    public static function isField(string $field): bool
    {
        return isset(static::getTableColumns()[$field]);
    }

    /**
     * 模型填充数据.
     */
    public static function fillData(array $attributes, array|Model $parent = null): array
    {
        $model = static::getInstance();
        // 模型填充
        $attributes = $model->fill($attributes)->getAttributes();
        // 添加主键
        if (empty($attributes[$model->getKeyName()])) {
            $attributes[$model->getKeyName()] = generateId();
        }
        // 添加时间
        if ($model->usesTimestamps()) {
            $datetime = Carbon::now();
            if (empty($attributes['created_at'])) {
                $attributes['created_at'] = $datetime->toDateTimeString();
            }
            if (empty($attributes['updated_at'])) {
                $attributes['updated_at'] = $datetime->toDateTimeString();
            }
        }
        // 添加父级
        if (isset($parent['id'], $parent['level'], $parent['node'])) {
            $attributes['parent_id'] = $parent['id'];
            $attributes['level'] = $parent['level'] + 1;
            $attributes['node'] = $parent['node'] . $parent['id'] . '_';
        }

        return $attributes;
    }

    /**
     * 获取批量数据。
     */
    public static function getBatchData(array $items, array|Model $parent = null, array $common = []): array
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = static::fillData($common + $item, $parent);
        }

        return $data;
    }

    /**
     * 批量插入数据。
     */
    public static function batchDataInsert(array $items, array|Model $parent = null, array $common = []): bool
    {
        return static::insert(static::getBatchData($items, $parent, $common));
    }

    /**
     * 当前模型实例。
     */
    public static function getInstance(): Model
    {
        return new static();
    }
}
