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
 * @property int $enable
 * @property string $enable_alias
 * @property string $enable_name
 * @property string $enable_title
 */
trait EnableAttribute
{
    public function getEnableNameAttribute(): string
    {
        return getConstantValueName('enable', $this->enable);
    }

    public function getEnableAliasAttribute(): string
    {
        return $this->enable_name;
    }

    public function getEnableTitleAttribute(): string
    {
        return getConstantValueTitle('enable', $this->enable);
    }

    public function setEnableAttribute($value): void
    {
        if (! is_bool($value)) {
            $value = $this->getEnableValue((string) $value);
        }
        $this->attributes['enable'] = (int) $value;
    }

    public function getEnableValue(string $name): int
    {
        return getConstantNameValue('enable', $name);
    }
}
