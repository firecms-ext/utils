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
namespace FirecmsExt\Utils\Amqp\Producer\Log;

use FirecmsExt\Utils\Amqp\Producer\AbstractProducerMessage;
use Hyperf\Amqp\Annotation\Producer;

#[Producer]
class RequestLogProducer extends AbstractProducerMessage
{
}
