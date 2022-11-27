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
namespace App\Model\Traits\Attribute;

use Hyperf\Database\Model\Builder;

/**
 * @property int $module
 * @property string $module_name
 * @property string $module_alias
 * @property string $module_title
 */
trait ModuleAttribute
{
    public function getModuleNameAttribute(): string
    {
        return getConstantValueName('module', $this->module);
    }

    public function getModuleAliasAttribute(): string
    {
        return $this->module_name;
    }

    public function getModuleTitleAttribute(): string
    {
        return getConstantValueTitle('module', $this->module);
    }

    public function setModuleAttribute($value): void
    {
        if (! is_numeric($value) && $value) {
            $value = $this->getModuleValue((string) $value);
        }
        $this->attributes['module'] = (int) $value;
    }

    public function getModuleValue(string $name): int
    {
        return getConstantNameValue('module', $name);
    }

    public function scopeQueryModule(Builder $query, int|string $value): Builder
    {
        if (! is_numeric($value) && $value) {
            $value = $this->getModuleValue($value);
        }

        return $query->where('module', (int) $value);
    }
}
