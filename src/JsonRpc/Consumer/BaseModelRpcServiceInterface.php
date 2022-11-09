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

interface BaseModelRpcServiceInterface
{
    public function check(array $authorization): bool;

    public function checkPassword(array $authorization, string $password): bool;

    public function id(array $authorization): ?string;

    public function user(array $authorization): ?array;

    public function username(array $authorization): ?string;
}
