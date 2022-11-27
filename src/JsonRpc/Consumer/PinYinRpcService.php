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

class PinYinRpcService extends AbstractServiceClient implements PinYinRpcServiceInterface
{
    public function sentence(string $str, string $toneStyle = 'none'): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function permalink(string $str, string $delimiter = '-'): string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function abbr(string $str, bool $asName = false): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function nameAbbr(string $str): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function name(string $str, string $toneStyle = 'none'): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function polyphones(string $str, string $toneStyle = 'none'): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
