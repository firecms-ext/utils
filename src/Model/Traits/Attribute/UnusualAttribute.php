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
 * @property int $unusual
 * @property string $unusual_name
 * @property string $unusual_alias
 * @property string $unusual_title
 */
trait UnusualAttribute
{
    public function getUnusualNameAttribute(): string
    {
        return getConstantValueName('unusual', $this->unusual);
    }

    public function getUnusualAliasAttribute(): string
    {
        return $this->unusual_name;
    }

    public function getUnusualTitleAttribute(): string
    {
        return getConstantValueTitle('unusual', $this->unusual);
    }

    public function setUnusualAttribute($value): void
    {
        if (! is_bool($value)) {
            $value = $this->getUnusualValue((string) $value);
        }
        $this->attributes['unusual'] = (int) $value;
    }

    public function getUnusualValue(string $name): int
    {
        return getConstantNameValue('unusual', $name);
    }
}
