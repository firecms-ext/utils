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

class CategoryRpcService extends AbstractServiceClient implements CategoryRpcServiceInterface
{
    public function treeOptions(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function modelTypeOptions(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
