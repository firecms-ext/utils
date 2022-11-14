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

/**
 * 发布/取消.
 */
class PublishExpiredRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'publish' => ['required', 'boolean'],
            'publish_at' => ['nullable', 'date_format:Y-m-d H:i'],
            'expired_at' => ['nullable', 'date_format:Y-m-d H:i', 'after:publish_at'],
        ];
    }
}
