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

use Carbon\Carbon;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property Carbon $deleted_at
 */
abstract class SoftDeleteModel extends Model
{
    use SoftDeletes;

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
        'deleted_at' => 'datetime',
    ];
}
