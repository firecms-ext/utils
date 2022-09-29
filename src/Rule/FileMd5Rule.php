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

use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Validator;

class FileMd5Rule
{
    public function __construct(ValidatorFactoryInterface $validatorFactory)
    {
        $validatorFactory->extend('file_md5', function ($attribute, $value, $parameters, $validator) {
            /* @var Validator $validator */
            /* @var UploadedFile $value */
            $result = hash_equals(md5_file($value->getRealPath()), $validator->getData()[array_pop($parameters) ?: 'md5']);

            if (! $result) {
                $validator->setCustomMessages([
                    $attribute . '.file_md5' => ':attribute 数据无效（或缺失）',
                ]);
            }

            return $result;
        });
    }
}
