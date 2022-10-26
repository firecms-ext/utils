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
namespace FirecmsExt\Utils\Amqp\Consumer;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Result;

#[Consumer(name: 'DemoConsumer', nums: 1)]
class DemoConsumer extends AbstractConsumerMessage
{
    public function consume($data): string
    {
        // 消费消息逻辑

        return Result::ACK;
    }
}
