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
 * 启用/停用.
 */
class EnableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'enable' => ['required', 'boolean'],
        ];
    }
}
