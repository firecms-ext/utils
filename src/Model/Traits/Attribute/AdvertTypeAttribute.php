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
 * @property int $advert_type
 * @property string $advert_type_name
 * @property string $advert_type_alias
 * @property string $advert_type_title
 */
trait AdvertTypeAttribute
{
    public function getAdvertTypeNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('advert_type', (int) $this->advert_type);
    }

    public function getAdvertTypeAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('advert_type', (int) $this->advert_type);
    }

    public function getAdvertTypeTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('advert_type', (int) $this->advert_type);
    }

    public function setAdvertTypeAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getAdvertTypeValue((string) $value);
        }
        $this->attributes['advert_type'] = (int) $value;
    }

    public function getAdvertTypeValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('advert_type', $name);
    }
}
