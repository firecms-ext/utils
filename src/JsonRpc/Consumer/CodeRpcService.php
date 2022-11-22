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
namespace FirecmsExt\Utils\JsonRpc\Consumer;

class CodeRpcService extends AbstractServiceClient implements CodeRpcServiceInterface
{
    /** 图片验证码 */
    public function captcha(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    /** 邮件验证码 */
    public function mail(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }

    /** 短信验证码 */
    public function sms(array $params): array
    {
        return $this->__request(__FUNCTION__, func_get_args());
    }
}
