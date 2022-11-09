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

interface AbstractConstantServiceClientInterface
{
    public function options(array $params): array;

    public function items(string $category_name): array;

    public function names(string $category_name): array;

    public function values(string $category_name): array;

    public function value(string $category_name, string $name): ?int;

    public function name(string $category_name, int $value): ?string;

    public function alias(string $category_name, int $value): ?string;

    public function message(string $category_name, int $value): ?string;

    public function title(string $category_name, int $value): ?string;

    public function all(): array;
}
