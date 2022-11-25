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
 * @property int $top
 * @property string $top_name
 * @property string $top_title
 */
trait TopAttribute
{
    public function getTopNameAttribute(): string
    {
        return getConstantValueName('top', $this->top);
    }

    public function getTopAliasAttribute(): string
    {
        return $this->top_name;
    }

    public function getTopTitleAttribute(): string
    {
        return getConstantValueTitle('top', $this->top);
    }

    public function setTopAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getTopValue((string) $value);
        }
        $this->attributes['top'] = (int) $value;
    }

    public function getTopValue(string $name): int
    {
        return getConstantNameValue('top', $name);
    }
}
