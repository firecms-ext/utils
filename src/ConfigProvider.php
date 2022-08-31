<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsExt Demo.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 * @license  https://gitee.com/firecms-ext/demo/blob/master/LICENSE
 */
namespace FirecmsExt\Utils;

use FirecmsExt\Utils\Commands\GenAppKeyCommand;
use FirecmsExt\Utils\Contracts\EncrypterServiceInterface;
use FirecmsExt\Utils\Services\EncrypterService;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                EncrypterServiceInterface::class => EncrypterService::class,
            ],
            'commands' => [
                GenAppKeyCommand::class,
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
