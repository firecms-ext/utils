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
 * 清理.
 */
class ClearRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_at' => ['nullable', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
        ];
    }
}
