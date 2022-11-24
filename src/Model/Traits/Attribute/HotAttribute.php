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

use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;

/**
 * 热门.
 * @property int $hot
 * @property string $hot_alias
 * @property string $hot_name
 * @property string $hot_title
 */
trait HotAttribute
{
    public function getHotNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('hot', (int) $this->hot);
    }

    public function getHotAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('hot', (int) $this->hot);
    }

    public function getHotTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('hot', (int) $this->hot);
    }

    public function setHotAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getHotValue((string) $value);
        }
        $this->attributes['hot'] = (int) $value;
    }

    public function getHotValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('hot', $name);
    }
}
