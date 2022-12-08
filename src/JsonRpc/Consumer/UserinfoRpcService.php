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

class UserinfoRpcService extends AbstractServiceClient implements UserinfoRpcServiceInterface
{
    public function password(string $password, string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function avatar(string $avatar, string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function imagePhoto(string $image_photo, string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function identifyPhoto(string $identify_photo, string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function mail(string $email, string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function phone(string $mobile, string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getUserinfo(string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function setUserinfo(array $params, string $id): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getMenu(string $id, int $module, ?string $parent_name = null, ?string $resourceClass = null): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getEmail(string $user_id): string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    public function getMobile(string $user_id): string
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
