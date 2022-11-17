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

class RegionRpcService extends AbstractServiceClient implements RegionRpcServiceInterface
{
    public function options(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function currentCity(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function search($params, int $page = 1, int $limit = -1): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
