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

class AuthRpcService extends AbstractServiceClient implements AuthRpcServiceInterface
{
    public function check(array $authorization): bool
    {
        return $this->__request(__FUNCTION__, compact('authorization'));
    }

    public function checkPassword(array $authorization, string $password): bool
    {
        return $this->__request(__FUNCTION__, compact('authorization', 'password'));
    }

    public function id(array $authorization): ?string
    {
        return $this->__request(__FUNCTION__, compact('authorization'));
    }

    public function user(array $authorization): ?array
    {
        return $this->__request(__FUNCTION__, compact('authorization'));
    }

    public function username(array $authorization): ?string
    {
        return $this->__request(__FUNCTION__, compact('authorization'));
    }
}
