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
 * @property int $repeal
 * @property string $repeal_name
 * @property string $repeal_alias
 * @property string $repeal_title
 */
trait RepealAttribute
{
    public function getRepealNameAttribute(): string
    {
        return getConstantValueName('repeal', $this->repeal);
    }

    public function getRepealAliasAttribute(): string
    {
        return $this->repeal_name;
    }

    public function getRepealTitleAttribute(): string
    {
        return getConstantValueTitle('repeal', $this->repeal);
    }

    public function setRepealAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false, '0', '1']) && $value) {
            $value = $this->getRepealValue((string) $value);
        }
        $this->attributes['repeal'] = (int) $value;
    }

    public function getRepealValue(string $name): int
    {
        return getConstantNameValue('repeal', $name);
    }
}
