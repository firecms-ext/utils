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
namespace FirecmsExt\Utils\Resource;

/**
 * 分页信息.
 */
class PaginatedResponse extends \Hyperf\Resource\Response\PaginatedResponse
{
    protected function paginationInformation(): array
    {
        return [
            'total' => $this->resource->resource->total(),
        ];
    }
}
