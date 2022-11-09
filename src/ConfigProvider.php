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

use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\AuthRpcServiceInterface;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcService;
use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AuthRpcServiceInterface::class => AuthRpcService::class,
                ConstantRpcServiceInterface::class => ConstantRpcService::class,
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
