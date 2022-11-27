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
namespace FirecmsExt\Utils\Listener;

use Hyperf\Crontab\Event\FailToExecute;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Psr\Container\ContainerInterface;

#[Listener]
class FailToExecuteCrontabListener implements ListenerInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            FailToExecute::class,
        ];
    }

    public function process(object $event)
    {
        /* @var FailToExecute $event */
        ddd(
            '异常触发类：' . $event->crontab->getName(),
            '异常的原因：' . $event->throwable->getMessage()
        );
        // 通知技术员：
    }
}
