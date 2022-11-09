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

class TranslationRpcService extends AbstractServiceClient implements TranslationRpcServiceInterface
{
    public function translations(string $category_name, string $local = 'zh_CN'): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function all(): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
