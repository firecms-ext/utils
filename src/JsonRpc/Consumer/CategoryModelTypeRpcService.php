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

class CategoryModelTypeRpcService extends AbstractServiceClient implements CategoryModelTypeRpcServiceInterface
{
    public function values(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function names(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function options(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function items(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
