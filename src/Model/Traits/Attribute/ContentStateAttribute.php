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

use Hyperf\Database\Model\Builder;

/**
 * @property int $state
 * @property string $state_name
 * @property string $state_alias
 * @property string $state_title
 * @method static queryStateName($value)
 */
trait ContentStateAttribute
{
    public function getStateNameAttribute(): string
    {
        return getConstantValueName('content_state', (int) $this->state);
    }

    public function getStateAliasAttribute(): string
    {
        return $this->state_name;
    }

    public function getStateTitleAttribute(): string
    {
        return getConstantValueTitle('content_state', (int) $this->state);
    }

    public function setStateAttribute($value): void
    {
        if (! is_numeric($value) && $value) {
            $value = $this->getStateValue((string) $value);
        }
        $this->attributes['state'] = (int) $value;
    }

    public function getStateValue(string $name): int
    {
        return getConstantNameValue('content_state', $name);
    }

    public function scopeQueryStateName(Builder $query, string $value = 'Accept'): Builder
    {
        return $query->when($value, function ($query, $value) {
            return $query->where('state', $this->getStateValue($value));
        });
    }
}
