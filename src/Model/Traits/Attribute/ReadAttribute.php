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
 * @property int $read
 * @property string $read_alias
 * @property string $read_name
 * @property string $read_title
 */
trait ReadAttribute
{
    public function getReadNameAttribute(): string
    {
        return getConstantValueName('read', $this->read);
    }

    public function getReadAliasAttribute(): string
    {
        return $this->read_name;
    }

    public function getReadTitleAttribute(): string
    {
        return getConstantValueTitle('read', $this->read);
    }

    public function setReadAttribute($value): void
    {
        if (! in_array($value, [0, 1]) && $value) {
            $value = $this->getReadValue((string) $value);
        }
        $this->attributes['read'] = (int) $value;
    }

    public function getReadValue(string $name): int
    {
        return getConstantNameValue('read', $name);
    }
}
