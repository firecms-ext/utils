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
 * @property int $directly
 * @property string $directly_name
 * @property string $directly_alias
 * @property string $directly_title
 */
trait DirectlyAttribute
{
    public function getDirectlyNameAttribute(): string
    {
        return getConstantValueName('directly', $this->directly);
    }

    public function getDirectlyAliasAttribute(): string
    {
        return $this->directly_name;
    }

    public function getDirectlyTitleAttribute(): string
    {
        return getConstantValueTitle('directly', $this->directly);
    }

    public function setDirectlyAttribute($value): void
    {
        if (! in_array($value, [0, 1]) && $value) {
            $value = $this->getDirectlyValue((string) $value);
        }
        $this->attributes['directly'] = (int) $value;
    }

    public function getDirectlyValue(string $name): int
    {
        return getConstantNameValue('directly', $name);
    }
}
