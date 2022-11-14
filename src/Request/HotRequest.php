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
 * 热门/取消.
 */
class HotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'hot' => ['required', 'boolean'],
        ];
    }
}
