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

    public function getCategories($model_type, $type): array;

    public function getHotCategories(string $model_type): array;

    public function getRecommendCategories(string $model_type): array;
}
