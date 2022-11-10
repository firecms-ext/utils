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
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Result;

#[Consumer(name: 'TranslationGenerateConsumer', nums: 1)]
class TranslationGenerateConsumer extends AbstractConsumerMessage
{
    protected $type = Type::FANOUT;

    public function consume($data): string
    {
        // 消费消息逻辑
        foreach ($data['items'] as $item) {
            if ($item['path'] ?? null) {
                if (! is_dir($item['path'])) {
                    @mkdir($item['path'], 0777, true);
                }
                foreach ($item['data'] as $key => $datum) {
                    $path = $item['path'] . $key . '.php';
                    $str = '<?php' . PHP_EOL . PHP_EOL;
                    $str .= 'declare(strict_types=1);' . PHP_EOL . PHP_EOL;
                    $str .= 'return ' . var_export($datum, true) . ';';

                    file_put_contents($path, $str);
                }
            }
        }
        return Result::ACK;
    }
}
