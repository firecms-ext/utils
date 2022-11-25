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
 * @property int $input_type
 * @property string $input_type_name
 * @property string $input_type_alias
 * @property string $input_type_title
 */
trait InputTypeAttribute
{
    public function getInputTypeNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('input_type', (int) $this->input_type);
    }

    public function getInputTypeTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('input_type', (int) $this->input_type);
    }

    public function getInputTypeAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('input_type', (int) $this->input_type);
    }

    public function setInputTypeAttribute($value): void
    {
        if (! is_numeric($value)) {
            $value = $this->getInputTypeValue((string) $value);
        }
        $this->attributes['input_type'] = (int) $value;
    }

    public function getInputTypeValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('input_type', $name);
    }
}
