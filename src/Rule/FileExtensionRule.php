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
namespace FirecmsExt\Utils\Rule;

use Hyperf\Utils\Str;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Validator;

class FileExtensionRule
{
    public function __construct(ValidatorFactoryInterface $validatorFactory)
    {
        // 扩展名检查
        $validatorFactory->extend('file_extension', function ($attribute, $value, $parameters, $validator) {
            /* @var Validator $validator */
            $result = in_array(Str::lower(extension($value)), array_map(function ($parameter) {
                return Str::lower($parameter);
            }, $parameters));
            if (! $result) {
                $validator->setCustomMessages([
                    $attribute . '.file_extension' => ':attribute 仅支持以下扩展名：' . implode(',', $parameters),
                ]);
            }
            return $result;
        });
    }
}
