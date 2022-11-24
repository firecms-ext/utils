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
 * @property int $read
 * @property string $read_alias
 * @property string $read_name
 * @property string $read_title
 */
trait ReadAttribute
{
    public function getReadNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('read', (int) $this->read);
    }

    public function getReadAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('read', (int) $this->read);
    }

    public function getReadTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('read', (int) $this->read);
    }

    public function setReadAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getReadValue((string) $value);
        }
        $this->attributes['read'] = (int) $value;
    }

    public function getReadValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('read', $name);
    }
}
