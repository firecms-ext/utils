<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsLaw Notify.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 * @license  https://gitee.com/firecms-law/notify/blob/master/LICENSE
 */
namespace FirecmsExt\Utils\JsonRpc\Consumer;

interface SettingRpcServiceInterface
{
    public function group(string $group): ?array;

    public function value(string $group, string $name): ?string;
}
