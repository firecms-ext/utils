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
 * @property int $exception_log_state
 * @property string $exception_log_state_name
 * @property string $exception_log_state_alias
 * @property string $exception_log_state_title
 */
trait ExceptionLogStateAttribute
{
    public function getExceptionLogStateNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('exception_log_state', (int) $this->exception_log_state);
    }

    public function getExceptionLogStateAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('exception_log_state', (int) $this->exception_log_state);
    }

    public function getExceptionLogStateTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('exception_log_state', (int) $this->exception_log_state);
    }

    public function setExceptionLogStateAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getExceptionLogStateValue((string) $value);
        }
        $this->attributes['exception_log_state'] = (int) $value;
    }

    public function getExceptionLogStateValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('exception_log_state', $name);
    }
}
