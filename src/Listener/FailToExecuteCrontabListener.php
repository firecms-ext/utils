<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsLaw Crawl.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
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
