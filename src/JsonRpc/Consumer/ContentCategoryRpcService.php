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

class ContentCategoryRpcService extends AbstractServiceClient implements ContentCategoryRpcServiceInterface
{
    public function treeOptions(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function modelTypeOptions(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getCategories(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function publishItems(array $params): array
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
}
