<?php

namespace FirecmsExt\Utils\JsonRpc\Provider;

interface AbstractAuthServiceInterface
{
    public function check(array $authorization): bool;

    public function checkPassword(array $authorization, string $password): bool;

    public function id(array $authorization): ?string;

    public function user(array $authorization): ?array;

    public function username(array $authorization): ?string;
}