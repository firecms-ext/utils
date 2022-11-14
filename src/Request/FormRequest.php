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
namespace FirecmsExt\Utils\Request;

use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Hyperf\Validation\Request\FormRequest as BaseFormRequest;

/**
 * 抽象请求基础
 */
abstract class FormRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedAuthorization()
    {
        throw new UnauthorizedHttpException(__('message.This action is unauthorized'));
    }
}
