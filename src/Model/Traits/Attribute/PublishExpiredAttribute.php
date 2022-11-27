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
namespace FirecmsExt\Utils\Model\Traits\Attribute;

use Carbon\Carbon;
use Hyperf\Database\Model\Builder;

/**
 * @property int $publish
 * @property string $expired_at
 * @method static static queryPublish()
 */
trait PublishExpiredAttribute
{
    use PublishAttribute;

    public function setExpiredAtAttribute($value): void
    {
        $this->attributes['expired_at'] = ($this->publish && $value) ? $value : null;
    }

    public function setPublishAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false, '0', '1']) && $value) {
            $value = $this->getPublishValue((string) $value);
        }
        $this->attributes['publish'] = $value = (int) $value;
        if (! $value) {
            $this->attributes['publish_at'] = null;
            $this->attributes['expired_at'] = null;
        }
    }

    public function scopeQueryPublish(Builder $query): Builder
    {
        return $query->where('publish', true)
            ->where('publish_at', '<=', Carbon::now())
            ->where(function ($query) {
                $query->where('expired_at', '>=', Carbon::now())
                    ->orWhereNull('expired_at');
            });
    }
}
