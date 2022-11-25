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
namespace FirecmsExt\Utils\Model\Traits\Attribute;

/**
 * @property array $user
 * @property array $userinfo
 * @property string $user_id
 * @property string $username
 * @property string $nickname
 * @property string $avatar
 * @property string $image_photo
 * @property string $identify_photo
 */
trait UserAttribute
{
    public function getUserAttribute(): array
    {
        return userInfo((string) $this->user_id, ['info']);
    }

    public function getUserinfoAttribute(): array
    {
        return $this->user['info'] ?? [];
    }

    public function getUsernameAttribute(): string
    {
        return $this->user['username'] ?? '';
    }

    public function getNicknameAttribute(): string
    {
        return $this->user['info']['nickname'] ?? '';
    }

    public function getAvatarAttribute(): string
    {
        return $this->user['info']['avatar'] ?? getSetting('default_image', 'avatar');
    }

    public function getImagePhotoAttribute(): string
    {
        return $this->user['info']['image_photo'] ?? getSetting('default_image', 'image_photo');
    }

    public function getIdentifyPhotoAttribute(): string
    {
        return $this->user['info']['identify_photo'] ?? getSetting('default_image', 'identify_photo');
    }
}
