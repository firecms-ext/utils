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
namespace FirecmsExt\Utils\Exception;

use FirecmsExt\Utils\Constants\HttpCode;
use Hyperf\Server\Exception\ServerException;

class BusinessException extends ServerException
{
    public function __construct(int $code = 0, string $message = null, \Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = HttpCode::getMessage($code);
        }

        parent::__construct($message, $code, $previous);
    }
}
