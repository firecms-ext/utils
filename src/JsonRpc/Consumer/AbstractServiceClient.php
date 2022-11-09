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
namespace FirecmsExt\Utils\JsonRpc\Consumer;

use Psr\Container\ContainerInterface;

abstract class AbstractServiceClient extends \Hyperf\RpcClient\AbstractServiceClient
{
    public function __construct(ContainerInterface $container)
    {
        $this->serviceName = $this->serviceName ?: class_basename(static::class);

        parent::__construct($container);
    }
}
