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
 * @property int $recommend
 * @property string $recommend_name
 * @property string $recommend_title
 */
trait RecommendAttribute
{
    public function getRecommendNameAttribute(): string
    {
        return getConstantValueName('recommend', $this->recommend);
    }

    public function getRecommendAliasAttribute(): string
    {
        return $this->recommend_name;
    }

    public function getRecommendTitleAttribute(): string
    {
        return getConstantValueTitle('recommend', $this->recommend);
    }

    public function setRecommendAttribute($value): void
    {
        if (! is_bool($value)) {
            $value = $this->getRecommendValue((string) $value);
        }
        $this->attributes['recommend'] = (int) $value;
    }

    public function getRecommendValue(string $name): int
    {
        return getConstantNameValue('recommend', $name);
    }
}
