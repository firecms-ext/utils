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
 * @property int $display
 * @property string $display_name
 * @property string $display_alias
 * @property string $display_title
 */
trait DisplayAttribute
{
    public function getDisplayNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('display', (int) $this->display);
    }

    public function getDisplayAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('display', (int) $this->display);
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('display', (int) $this->display);
    }

    public function setDisplayAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getDisplayValue((string) $value);
        }
        $this->attributes['display'] = (int) $value;
    }

    public function getDisplayValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('display', $name);
    }
}
