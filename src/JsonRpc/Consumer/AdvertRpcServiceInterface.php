<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsLaw Home-Http.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 */
namespace FirecmsExt\Utils\JsonRpc\Consumer;

interface AdvertRpcServiceInterface
{
    public function publishList(array $params): array;

    public function publishOne(array $params): array;
}
