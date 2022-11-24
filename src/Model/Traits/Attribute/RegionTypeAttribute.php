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
 * @property int $region_type
 * @property string $region_type_name
 * @property string $region_type_alias
 * @property string $region_type_title
 */
trait RegionTypeAttribute
{
    public function getRegionTypeNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('region_type', (int) $this->region_type);
    }

    public function getRegionTypeAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('region_type', (int) $this->region_type);
    }

    public function getRegionTypeTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('region_type', (int) $this->region_type);
    }

    public function setRegionTypeAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getRegionTypeValue((string) $value);
        }
        $this->attributes['region_type'] = (int) $value;
    }

    public function getRegionTypeValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('region_type', $name);
    }
}
