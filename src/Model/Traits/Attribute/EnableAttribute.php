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
 * @property int $enable
 * @property string $enable_alias
 * @property string $enable_name
 * @property string $enable_title
 */
trait EnableAttribute
{
    public function getEnableNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('enable', (int) $this->enable);
    }

    public function getEnableAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('enable', (int) $this->enable);
    }

    public function getEnableTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('enable', (int) $this->enable);
    }

    public function setEnableAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getEnableValue((string) $value);
        }
        $this->attributes['enable'] = (int) $value;
    }

    public function getEnableValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('enable', $name);
    }
}
