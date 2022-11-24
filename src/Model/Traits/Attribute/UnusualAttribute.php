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

use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;

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
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('unusual', (int) $this->unusual);
    }

    public function getUnusualAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('unusual', (int) $this->unusual);
    }

    public function getUnusualTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('unusual', (int) $this->unusual);
    }

    public function setUnusualAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getUnusualValue((string) $value);
        }
        $this->attributes['unusual'] = (int) $value;
    }

    public function getUnusualValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('unusual', $name);
    }
}
