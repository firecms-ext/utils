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
 * @property int $gender
 * @property string $gender_alias
 * @property string $gender_name
 * @property string $gender_title
 */
trait GenderAttribute
{
    public function getGenderNameAttribute(): string
    {
        return getConstantValueName('gender', $this->gender);
    }

    public function getGenderAliasAttribute(): string
    {
        return $this->gender_name;
    }

    public function getGenderTitleAttribute(): string
    {
        return getConstantValueTitle('gender', $this->gender);
    }

    public function setGenderAttribute($value): void
    {
        if (! in_array($value, getConstantValues('gender'))) {
            $value = $this->getGenderValue((string) $value);
        }
        $this->attributes['gender'] = (int) $value;
    }

    public function getGenderValue(string $name): int
    {
        return getConstantNameValue('gender', $name);
    }
}
