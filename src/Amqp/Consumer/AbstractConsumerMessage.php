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

use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Message\Type;
use Hyperf\Utils\Str;
use PhpAmqpLib\Message\AMQPMessage;

abstract class AbstractConsumerMessage extends ConsumerMessage
{
    public function __construct()
    {
        $this->routingKey = Str::snake(str_replace(['App\\Amqp\\Consumer', 'Consumer', '\\'], '', static::class));
        if ($this->getType() === Type::FANOUT) {
            $this->exchange = config('app_prefix', 'firecms') . '.'
                . $this->getType() . '.' . $this->routingKey;
        } else {
            $this->exchange = config('app_prefix', 'firecms') . '.' . $this->getType();
        }
        $this->queue = config('app_name') . '.' . $this->routingKey . '.' . config('app_queues', 'queues');
    }

    public function consumeMessage($data, AMQPMessage $message): string
    {
        var_dump('消费 MQ：' . static::class);

        return $this->consume($data);
    }
}
