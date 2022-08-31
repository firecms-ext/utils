<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsExt Demo.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 * @license  https://gitee.com/firecms-ext/demo/blob/master/LICENSE
 */
namespace FirecmsExt\Utils\Commands;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Contract\ConfigInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractGenCommand extends HyperfCommand
{
    protected ConfigInterface $config;

    protected string $description;

    public function __construct(ConfigInterface $config)
    {
        parent::__construct();

        $this->config = $config;
    }

    public function configure()
    {
        parent::configure();

        $this->setDescription($this->description);
    }

    /**
     * @return null|mixed
     */
    protected function getOption(string $name, mixed $default = null): mixed
    {
        $result = $this->input->getOption($name);
        return empty($result) ? $default : $result;
    }

    protected function envFilePath(): string
    {
        return BASE_PATH . '/.env';
    }
}
