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
     * 获取表名.
     */
    public static function getTableName(bool $prefix = false): string
    {
        return ($prefix ? self::getPrefix() : '') . self::getInstance()->getTable();
    }

    /**
     * 获取表 列信息.
     */
    public static function getTableColumns(): array
    {
        $items = [];
        foreach (Db::select('SHOW COLUMNS FROM ' . self::getTableName(true)) as $row) {
            $items[$row->Field] = $row;
        }

        return $items;
    }

    /**
     * 获取表 字段.
     * @return string[]
     */
    public static function getTableFields(): array
    {
        return array_keys(self::getTableColumns());
    }

    /**
     * 链接前缀。
     */
    public static function getPrefix(): string
    {
        return (string)static::query()->getConnection()->getTablePrefix();
    }

    /**
     * 是否包含字段
     * @param string $field
     * @return bool
     */
    public static function hasField(string $field): bool
    {
        return isset(self::getTableFields()[$field]);
    }

    /**
     * 模型填充数据.
     */
    public static function fillData(array $attributes, array|Model $parent = null): array
    {
        $model = self::getInstance();
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
        if ($parent) {
            $attributes['parent_id'] = $parent['id'];
            $attributes['level'] = $parent['level'] + 1;
            $attributes['node'] = $parent['node'] . $parent['id'] . '_';
        }

        return $attributes;
    }

    /**
     * 批量数据插入。
     */
    public static function batchDataInsert(array $items, array|Model $parent = null): bool
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = self::fillData($item, $parent);
        }

        return self::insert($data);
    }

    /**
     * 当前模型实例。
     */
    public static function getInstance(): Model
    {
        return new static();
    }
}
