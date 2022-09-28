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

use FirecmsExt\Utils\Rule\FileExtensionRule;
use FirecmsExt\Utils\Rule\FileMd5Rule;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;

#[Listener]
class ValidatorFactoryResolvedListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process(object $event)
    {
        /* @var ValidatorFactoryResolved $event */
        make(FileMd5Rule::class, ['validatorFactory' => $event->validatorFactory]);
        make(FileExtensionRule::class, ['validatorFactory' => $event->validatorFactory]);
    }
}
