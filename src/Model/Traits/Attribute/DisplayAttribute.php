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
 * @property int $display
 * @property string $display_name
 * @property string $display_alias
 * @property string $display_title
 */
trait DisplayAttribute
{
    public function getDisplayNameAttribute(): string
    {
        return getConstantValueName('display', $this->display);
    }

    public function getDisplayAliasAttribute(): string
    {
        return $this->display_name;
    }

    public function getDisplayTitleAttribute(): string
    {
        return getConstantValueTitle('display', $this->display);
    }

    public function setDisplayAttribute($value): void
    {
        if (! is_bool($value)) {
            $value = $this->getDisplayValue((string) $value);
        }
        $this->attributes['display'] = (int) $value;
    }

    public function getDisplayValue(string $name): int
    {
        return getConstantNameValue('display', $name);
    }
}
