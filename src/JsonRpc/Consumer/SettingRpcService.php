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


class SettingRpcService extends AbstractServiceClient implements SettingRpcServiceInterface
{
    public function group(string $group): ?array
    {
        return $this->__request(__FUNCTION__, compact('group'));
    }

    public function value(string $group, string $name): ?string
    {
        return $this->__request(__FUNCTION__, compact('group', 'name'));
    }
}
