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

class CosRpcService extends AbstractServiceClient implements CosRpcServiceInterface
{
    public function getConfig(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getTempKeys(string $folder = 'files'): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getList(string $folder = 'files'): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
