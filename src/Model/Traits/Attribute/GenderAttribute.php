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
 * @property int $gender
 * @property string $gender_alias
 * @property string $gender_name
 * @property string $gender_title
 */
trait GenderAttribute
{
    public function getGenderNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('gender', (int) $this->gender);
    }

    public function getGenderAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('gender', (int) $this->gender);
    }

    public function getGenderTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('gender', (int) $this->gender);
    }

    public function setGenderAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getGenderValue((string) $value);
        }
        $this->attributes['gender'] = (int) $value;
    }

    public function getGenderValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('gender', $name);
    }
}
