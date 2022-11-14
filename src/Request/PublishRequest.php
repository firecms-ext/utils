<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsLaw Admin-Http.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 */
namespace FirecmsExt\Utils\Request;

/**
 * 发布/取消.
 */
class PublishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'publish' => ['required', 'boolean'],
            'publish_at' => ['nullable', 'date_format:Y-m-d H:i'],
        ];
    }
}
