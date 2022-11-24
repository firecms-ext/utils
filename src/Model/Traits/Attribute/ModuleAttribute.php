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

use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;
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
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('module', (int) $this->module);
    }

    public function getModuleAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('module', (int) $this->module);
    }

    public function getModuleTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('module', (int) $this->module);
    }

    public function setModuleAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getModuleValue((string) $value);
        }
        $this->attributes['module'] = (int) $value;
    }

    public function getModuleValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('module', $name);
    }

    public function scopeQueryModule(Builder $query, int|string $value): Builder
    {
        if (! is_numeric($value)) {
            $value = app()->get(ConstantRpcServiceInterface::class)
                ->value('module', $value);
        }

        return $query->where('module', (int) $value);
    }
}
