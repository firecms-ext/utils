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
