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
 * @property array $meta
 */
trait MetaAttribute
{
    /**
     * 设置 Meta。
     */
    public function setMetaAttribute(mixed $value): void
    {
        $value = array_filter((array) $value);
        $this->attributes['meta'] = json_encode($value);
    }

    /**
     * 获取 Meta。
     */
    public function getMetaAttribute(mixed $value): array
    {
        return $value ? json_decode($value) : [];
    }
}
