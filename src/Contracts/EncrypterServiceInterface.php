<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsExt Demo.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 * @license  https://gitee.com/firecms-ext/demo/blob/master/LICENSE
 */
namespace FirecmsExt\Utils\Encrypter;

interface EncrypterServiceInterface
{
    /**
     * 加密给定的值。
     */
    public function encrypt(mixed $value, bool $serialize = true): string;

    /**
     * 解密给定的值。
     */
    public function decrypt(string $payload, bool $unserialize = true): mixed;

    /**
     * 获取加密程序当前正在使用的加密密钥。
     */
    public function getKey(): string;

    /**
     * 加密字符串而不序列化。
     */
    public function encryptString(string $value): string;

    /**
     * 解密给定的字符串而不进行反序列化。
     */
    public function decryptString(string $payload): string;
}
