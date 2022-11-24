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
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;
use Hyperf\Database\Model\Builder;

/**
 * @property int $publish
 * @property string $publish_name
 * @property string $publish_title
 * @property string $publish_at
 * @method static static queryPublish()
 */
trait PublishAttribute
{
    public function getPublishNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('publish', (int) $this->publish);
    }

    public function getPublishAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('publish', (int) $this->publish);
    }

    public function getPublishTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('publish', (int) $this->publish);
    }

    public function getPublishValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('publish', $name);
    }

    public function setPublishAtAttribute($value): void
    {
        if (! $value && $this->publish) {
            $value = Carbon::now();
        }
        $this->attributes['publish_at'] = $value;
    }

    public function setPublishAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getPublishValue((string) $value);
        }

        $this->attributes['publish'] = $value = (int) $value;
        if (! $value) {
            $this->attributes['publish_at'] = null;
        }
    }

    public function scopeQueryPublish(Builder $query): Builder
    {
        return $query->where('publish', true)
            ->where('publish_at', '<=', Carbon::now());
    }
}
