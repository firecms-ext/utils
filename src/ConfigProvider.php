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

use FirecmsExt\Utils\JsonRpc\Consumer\AuthModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\BaseModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\BaseModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\IpQueryRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\IpQueryRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\RegionRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\RegionRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\SettingRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\SettingRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\TranslationRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\TranslationRpcServiceInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AuthRpcServiceInterface::class => AuthRpcService::class,
                ConstantRpcServiceInterface::class => ConstantRpcService::class,
                IpQueryRpcServiceInterface::class => IpQueryRpcService::class,
                RegionRpcServiceInterface::class => RegionRpcService::class,
                SettingRpcServiceInterface::class => SettingRpcService::class,
                TranslationRpcServiceInterface::class => TranslationRpcService::class,
                AuthModelRpcServiceInterface::class => AuthModelRpcService::class,
                BaseModelRpcServiceInterface::class => BaseModelRpcService::class,
                ContentModelRpcServiceInterface::class => ContentModelRpcService::class,
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
