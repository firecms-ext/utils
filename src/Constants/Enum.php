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
namespace FirecmsExt\Utils\Constants;

/**
 * @method static string getMessage($code, $translate = null)
 */
trait Enum
{
    protected static array $constCacheArray = [];

    /**
     * 价值对
     * key 和 value.
     * @throws \ReflectionException
     */
    public static function getValues(): array
    {
        // 获取类属性
        return array_values(self::getConstants());
    }

    /**
     * 价值对
     * value=>label.
     * @throws \ReflectionException
     */
    public static function getItems(): array
    {
        // 获取类属性 并 翻译
        $items = [];
        foreach (self::getConstants() as $value) {
            $items[$value] = self::getMessage($value);
        }
        return $items;
    }

    /**
     * 下拉选项
     * [value,label].
     * @throws \ReflectionException
     */
    public static function getOptions(): array
    {
        // 获取类属性 并 翻译
        $items = [];
        foreach (self::getConstants() as $value) {
            $items[] = [
                'value' => $value,
                'label' => self::getMessage($value),
            ];
        }
        return $items;
    }

    /**
     * 获取类上定义的所有常量。
     * @throws \ReflectionException
     */
    public static function getConstants(): array
    {
        $calledClass = get_called_class();

        if (! array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return static::$constCacheArray[$calledClass];
    }
}
