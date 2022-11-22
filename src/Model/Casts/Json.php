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
namespace FirecmsExt\Utils\Model\Casts;

use Hyperf\Contract\CastsAttributes;

class Json implements CastsAttributes
{
    /**
     * 将取出的数据进行转换.
     * @param mixed $model
     * @param mixed $key
     * @param mixed $value
     * @param mixed $attributes
     */
    public function get($model, $key, $value, $attributes)
    {
        return json_decode((string) $value, true);
    }

    /**
     * 转换成将要进行存储的值
     * @param mixed $model
     * @param mixed $key
     * @param mixed $value
     * @param mixed $attributes
     */
    public function set($model, $key, $value, $attributes)
    {
        return json_encode((array) $value);
    }
}
