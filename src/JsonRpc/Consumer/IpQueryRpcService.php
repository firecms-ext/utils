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

use Hyperf\RpcClient\AbstractServiceClient;

class IpQueryRpcService extends AbstractServiceClient implements IpQueryRpcServiceInterface
{
    public function query(string $ip): ?array
    {
        return $this->__request(__FUNCTION__, compact('ip'));
    }
}
