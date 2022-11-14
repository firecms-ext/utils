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
 * 排序.
 */
class SortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sort' => ['required', 'between:0,255'],
        ];
    }
}
