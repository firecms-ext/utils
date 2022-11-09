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

abstract class AbstractProducerMessage extends ProducerMessage
{
    public function __construct(array $data)
    {
        var_dump('MQ 投递：' . static::class);

        if (! $this->routingKey) {
            $this->routingKey = Str::snake(str_replace([
                'FirecmsExt\\Utils\\Amqp\\Producer',
                'App\\Amqp\\Producer',
                'Producer',
                '\\',
            ], '', static::class));
        }
        if (! $this->exchange) {
            if ($this->getType() === Type::FANOUT) {
                $this->exchange = config('app_prefix', 'firecms')
                    . '.' . $this->getType() . '.' . $this->routingKey;
            } else {
                $this->exchange = config('app_prefix', 'firecms') . '.' . $this->getType();
            }
        }

        $this->payload = $data;
    }
}
