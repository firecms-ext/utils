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
namespace FirecmsExt\Utils\Model\Traits\Attribute;

use FirecmsExt\Utils\JsonRpc\Consumer\ConstantRpcServiceInterface;

/**
 * @property int $crawl_state
 * @property string $crawl_state_name
 * @property string $crawl_state_alias
 * @property string $crawl_state_title
 */
trait CrawlStateAttribute
{
    public function getCrawlStateNameAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->name('crawl_state', (int) $this->crawl_state);
    }

    public function getCrawlStateAliasAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->alias('crawl_state', (int) $this->crawl_state);
    }

    public function getCrawlStateTitleAttribute(): string
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->title('crawl_state', (int) $this->crawl_state);
    }

    public function setCrawlStateAttribute($value): void
    {
        if (! in_array($value, [0, 1, true, false])) {
            $value = $this->getCrawlStateValue((string) $value);
        }
        $this->attributes['crawl_state'] = (int) $value;
    }

    public function getCrawlStateValue(string $name): ?int
    {
        return app()->get(ConstantRpcServiceInterface::class)
            ->value('crawl_state', $name);
    }
}
