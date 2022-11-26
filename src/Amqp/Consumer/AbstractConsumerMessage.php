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

/**
 * 订阅与发布模式(fanout)：
 * 生产者，一个交换机(fanoutExchange)，没有路由规则，多个队列，多个消费者。
 * 生产者将消息不是直接发送到队列，而是发送到X交换机，然后由交换机发送给两个队列，两个消费者各自监听一个队列，来消费消息。
 *
 * 路由模式(direct)：
 * 生产者，一个交换机(directExchange)，路由规则，多个队列，多个消费者。主要根据定义的路由规则决定消息往哪个队列发送。
 * 主题模式(topic)：
 * 生产者，一个交换机(topicExchange)，模糊匹配路由规则，多个队列，多个消费者。
 */
abstract class AbstractConsumerMessage extends ConsumerMessage
{
    public function __construct()
    {
        if (! $this->routingKey) {
            $this->routingKey = Str::snake(str_replace([
                'FirecmsExt\\Utils\\Amqp\\Consumer',
                'App\\Amqp\\Consumer',
                'Consumer',
                '\\',
            ], '', static::class));
        }

        if (! $this->exchange) {
            $this->exchange = config('app_prefix', 'firecms') . '.' . $this->getType();
            if ($this->type === Type::FANOUT) {
                $this->exchange .= '.' . $this->routingKey;
            }
        }

        if (! $this->queue) {
            $this->queue = config('app_queues', 'queues');
            if ($this->type === Type::FANOUT) {
                // 广播给不同队列
                $this->queue .= '.' . $this->routingKey;
            }
        }
    }

    public function consumeMessage($data, AMQPMessage $message): string
    {
        ddd('消费 MQ：' . static::class);

        return $this->consume($data);
    }
}
