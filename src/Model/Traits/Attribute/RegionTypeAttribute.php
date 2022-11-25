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

/**
 * @property int $type
 * @property string $type_name
 * @property string $type_alias
 * @property string $type_title
 */
trait RegionTypeAttribute
{
    public function getTypeNameAttribute(): string
    {
        return getConstantValueName('region_type', $this->type);
    }

    public function getTypeAliasAttribute(): string
    {
        return $this->type_name;
    }

    public function getTypeTitleAttribute(): string
    {
        return getConstantValueTitle('region_type', $this->type);
    }

    public function setTypeAttribute($value): void
    {
        if (! is_numeric($value)) {
            $value = $this->getTypeValue((string) $value);
        }
        $this->attributes['type'] = (int) $value;
    }

    public function getTypeValue(string $name): int
    {
        return getConstantNameValue('region_type', $name);
    }
}
