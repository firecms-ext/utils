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

use Hyperf\Resource\Json\ResourceCollection as BaseResourceCollection;
use Psr\Http\Message\ResponseInterface;

class ResourceCollection extends BaseResourceCollection
{
    public $wrap = 'items';

    public function toResponse(): ResponseInterface
    {
        if ($this->isPaginatorResource($this->resource)) {
            return (new PaginatedResponse($this))->toResponse();
        }

        return parent::toResponse();
    }
}
