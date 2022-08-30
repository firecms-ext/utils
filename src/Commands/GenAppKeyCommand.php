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

use Exception;
use FirecmsExt\Utils\Encrypter\EncrypterServiceService;
use Hyperf\Utils\Str;

class GenAppKeyCommand extends AbstractGenCommand
{
    protected $name = 'gen:app-key';

    protected string $description = 'Set the app key used to encrypter the tokens';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $cipher = $this->config->get('app_cipher', 'AES-256-CBC');
        $key = $this->generateRandomKey($cipher);
        if ($this->getOption('show')) {
            $this->comment($key);
            return;
        }
        if (file_exists($path = $this->envFilePath()) === false) {
            $this->displayKey($key);
            return;
        }
        if (Str::contains(file_get_contents($path), 'APP_KEY') === false) {
            file_put_contents($path, "\nAPP_KEY={$key}\n", FILE_APPEND);
        } else {
            if ($this->getOption('always-no')) {
                $this->comment('Secret key already exists. Skipping...');
                return;
            }

            if ($this->isConfirmed() === false) {
                $this->comment('Phew... No changes were made to your secret key.');
                return;
            }

            file_put_contents($path, preg_replace(
                "~APP_KEY=[^\n]*~",
                "APP_KEY=\"{$key}\"",
                file_get_contents($path)
            ));

            file_put_contents($path, preg_replace(
                "~APP_CIPHER=[^\n]*~",
                "APP_CIPHER=\"{$cipher}\"",
                file_get_contents($path)
            ));
        }

        $this->displayKey($key);
    }

    /**
     * 为应用程序生成一个随机密钥。
     * @throws Exception
     */
    protected function generateRandomKey(string $cipher): string
    {
        return 'base64:' . base64_encode(EncrypterServiceService::generateKey($cipher));
    }

    /**
     * 显示密钥.
     */
    protected function displayKey(string $key): void
    {
        $this->info("app key [<comment>{$key}</comment>] (base64 encoded) set successfully.");
    }

    /**
     * 是否确认.
     */
    protected function isConfirmed(): bool
    {
        return $this->getOption('force') || $this->confirm(
            'Are you sure you want to override the key? This will invalidate all existing tokens.'
        );
    }
}
