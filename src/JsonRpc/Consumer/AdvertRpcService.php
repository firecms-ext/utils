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

class AdvertRpcService extends AbstractServiceClient implements AdvertRpcServiceInterface
{
    public function publishItems(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishTopItems(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishHotItems(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishRecommendItems(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishList(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishTopList(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishHotList(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishRecommendList(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
