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
namespace FirecmsExt\Utils\Amqp\Producer;

use Hyperf\Amqp\Message\ProducerMessage;
use Hyperf\Amqp\Message\Type;
use Hyperf\Utils\Str;

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
abstract class AbstractProducerMessage extends ProducerMessage
{
    public function __construct(array $data)
    {
        if (! $this->routingKey) {
            $this->routingKey = str_replace('_', '.', Str::snake(str_replace([
                'FirecmsExt\\Utils\\Amqp\\Producer',
                'App\\Amqp\\Producer',
                'Producer',
                '\\',
            ], '', static::class)));
        }

        if (! $this->exchange) {
            $this->exchange = config('app_prefix', 'firecms') . '.' . $this->getType();
            if ($this->type === Type::FANOUT) {
                // 广播到不同交换机
                $this->exchange .= '.' . $this->routingKey;
            }
        }

        $this->payload = $data;

        ddd('MQ 投递：' . static::class);
    }
}
