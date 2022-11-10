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

interface CategoryModelTypeRpcServiceInterface
{
    public function values(): array;

    public function names(): array;

    public function options(): array;

    public function items(): array;
}
