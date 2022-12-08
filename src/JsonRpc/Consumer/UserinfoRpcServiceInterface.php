<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsLaw Admin-Http.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 */
namespace FirecmsExt\Utils\JsonRpc\Consumer;

interface UserinfoRpcServiceInterface
{
    /** 密码 */
    public function password(string $password, string $id): array;

    /** 头像 */
    public function avatar(string $avatar, string $id): array;

    /** 形象照 */
    public function imagePhoto(string $image_photo, string $id): array;

    /** 证件照 */
    public function identifyPhoto(string $identify_photo, string $id): array;

    /** 邮箱 */
    public function mail(string $email, string $id): array;

    /** 手机 */
    public function phone(string $mobile, string $id): array;

    /** 用户信息 */
    public function getUserinfo(string $id): array;

    /** 更新信息 */
    public function setUserinfo(array $params, string $id): array;

    /** 用户菜单 */
    public function getMenu(string $id, int $module, ?string $parent_name = null, ?string $resourceClass = null): array;

    /** 获取邮箱地址 */
    public function getEmail(string $user_id): string;

    /** 获取手机号 */
    public function getMobile(string $user_id): string;
}
