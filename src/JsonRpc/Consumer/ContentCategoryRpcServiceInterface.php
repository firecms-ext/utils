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

interface ContentCategoryRpcServiceInterface
{
    public function modelTypeOptions(): array;

    public function treeOptions(array $params): array;

    public function getCategories(array $params): array;

    public function publishItems(array $params): array;

    public function publishHotItems(array $params): array;

    public function publishRecommendItems(array $params): array;
}
