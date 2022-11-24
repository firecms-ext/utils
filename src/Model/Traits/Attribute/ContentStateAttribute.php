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
 * @property int $state
 * @property string $state_name
 * @property string $state_alias
 * @property string $state_title
 */
trait ContentStateAttribute
{
    public function getStateNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('content_state', (int) $this->state);
    }

    public function getStateAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('content_state', (int) $this->state);
    }

    public function getStateTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('content_state', (int) $this->state);
    }

    public function setStateAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getStateValue((string) $value);
        }
        $this->attributes['state'] = (int) $value;
    }

    public function getStateValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('content_state', $name);
    }
}