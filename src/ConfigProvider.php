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
namespace FirecmsExt\Utils;

use FirecmsExt\Utils\JsonRpc\Base\RegionRpcService;
use FirecmsExt\Utils\JsonRpc\Base\RegionRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\IpQueryRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\IpQueryRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\SettingRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\SettingRpcServiceInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AuthRpcServiceInterface::class => AuthRpcService::class,
                AuthModelRpcServiceInterface::class => AuthModelRpcService::class,
                ConstantRpcServiceInterface::class => ConstantRpcService::class,
                IpQueryRpcServiceInterface::class => IpQueryRpcService::class,
                RegionRpcServiceInterface::class => RegionRpcService::class,
                SettingRpcServiceInterface::class => SettingRpcService::class,
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
