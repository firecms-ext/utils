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
 * @property int $repeal
 * @property string $repeal_name
 * @property string $repeal_alias
 * @property string $repeal_title
 */
trait RepealAttribute
{
    public function getRepealNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('repeal', (int) $this->repeal);
    }

    public function getRepealAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('repeal', (int) $this->repeal);
    }

    public function getRepealTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('repeal', (int) $this->repeal);
    }

    public function setRepealAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getRepealValue((string) $value);
        }
        $this->attributes['repeal'] = (int) $value;
    }

    public function getRepealValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('repeal', $name);
    }
}
