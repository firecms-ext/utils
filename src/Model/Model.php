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
namespace FirecmsExt\Utils\Model;

use FirecmsExt\Utils\Model\Traits\TableUtils;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @method static queryKeyword($value)
 */
abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;
    use Snowflake;
    use TableUtils;

    /**
     * 属性类型转换。
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'model_id' => 'string',
        'parent_id' => 'string',
        'category_id' => 'string',
        'has_children' => 'integer',
        'level' => 'integer',
        'sort' => 'integer',
        'enable' => 'integer',
        'display' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 查询 关键词.
     */
    public function scopeQueryKeyword(Builder $query, ?string $value): Builder
    {
        return $query->when($value, function ($query, $value) {
            return $query->where($this->getKeyName(), $value);
        });
    }
}
