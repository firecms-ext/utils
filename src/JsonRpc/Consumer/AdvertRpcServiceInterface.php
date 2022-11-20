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

interface AdvertRpcServiceInterface
{
    public function publishItems(array $params): array;

    public function publishTopItems(array $params): array;

    public function publishHotItems(array $params): array;

    public function publishRecommendItems(array $params): array;

    public function publishList(array $params): array;

    public function publishTopList(array $params): array;

    public function publishHotList(array $params): array;

    public function publishRecommendList(array $params): array;
}
