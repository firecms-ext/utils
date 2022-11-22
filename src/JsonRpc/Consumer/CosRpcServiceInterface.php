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

interface CosRpcServiceInterface
{
    public function getTempKeys(string $folder = 'files'): array;

    public function getList(string $folder = 'files'): array;

    public function getConfig(): array;
}
