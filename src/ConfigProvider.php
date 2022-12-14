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

use FirecmsExt\Utils\JsonRpc\Consumer\AdvertRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AdvertRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\AuditModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuditModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\BaseModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\BaseModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\CategoryModelTypeRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\CategoryModelTypeRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\CategoryRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\CategoryRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\CodeRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\CodeRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentCategoryRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentCategoryRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentModelTypeRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ContentModelTypeRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\CosRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\CosRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\CrawlModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\CrawlModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\IpQueryRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\IpQueryRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\LogModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\LogModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\NotifyModelRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\NotifyModelRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\PinYinRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\PinYinRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\RegionRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\RegionRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\SettingRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\SettingRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\TranslationRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\TranslationRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\UserinfoRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\UserinfoRpcServiceInterface;

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
                CodeRpcServiceInterface::class => CodeRpcService::class,
                CosRpcServiceInterface::class => CosRpcService::class,
                SettingRpcServiceInterface::class => SettingRpcService::class,
                PinYinRpcServiceInterface::class => PinYinRpcService::class,
                TranslationRpcServiceInterface::class => TranslationRpcService::class,

                AdvertRpcServiceInterface::class => AdvertRpcService::class,

                CategoryModelTypeRpcServiceInterface::class => CategoryModelTypeRpcService::class,
                ContentModelTypeRpcServiceInterface::class => ContentModelTypeRpcService::class,

                CategoryRpcServiceInterface::class => CategoryRpcService::class,
                ContentCategoryRpcServiceInterface::class => ContentCategoryRpcService::class,

                AuthModelRpcServiceInterface::class => AuthModelRpcService::class,
                AuditModelRpcServiceInterface::class => AuditModelRpcService::class,
                BaseModelRpcServiceInterface::class => BaseModelRpcService::class,
                CrawlModelRpcServiceInterface::class => CrawlModelRpcService::class,
                ContentModelRpcServiceInterface::class => ContentModelRpcService::class,
                LogModelRpcServiceInterface::class => LogModelRpcService::class,
                NotifyModelRpcServiceInterface::class => NotifyModelRpcService::class,

                UserinfoRpcServiceInterface::class => UserinfoRpcService::class,
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
