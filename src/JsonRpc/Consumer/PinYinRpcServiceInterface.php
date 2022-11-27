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
namespace FirecmsExt\Utils\JsonRpc\Consumer;

interface PinYinRpcServiceInterface
{
    /**
     * 文字段落转拼音。
     */
    public function sentence(string $str, string $toneStyle = 'none'): array;

    /**
     * 生成用于链接的拼音字符串。
     */
    public function permalink(string $str, string $delimiter = '-'): string;

    /**
     * 获取首字符字符串。
     */
    public function abbr(string $str, bool $asName = false): array;

    /**
     * 姓名首字母。
     */
    public function nameAbbr(string $str): array;

    /**
     * 姓名转换。
     */
    public function name(string $str, string $toneStyle = 'none'): array;

    /**
     * 单字转拼音（多音字）。
     */
    public function polyphones(string $str, string $toneStyle = 'none'): array;
}
