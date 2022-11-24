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
 * @property int $content_state
 * @property string $content_state_name
 * @property string $content_state_alias
 * @property string $content_state_title
 */
trait ContentStateAttribute
{
    public function getContentStateNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('content_state', (int) $this->content_state);
    }

    public function getContentStateAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('content_state', (int) $this->content_state);
    }

    public function getContentStateTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('content_state', (int) $this->content_state);
    }

    public function setContentStateAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getContentStateValue((string) $value);
        }
        $this->attributes['content_state'] = (int) $value;
    }

    public function getContentStateValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('content_state', $name);
    }
}
