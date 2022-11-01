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
namespace HyperfTest\Cases;

/**
 * @internal
 * @coversNothing
 */
class FunctionTest extends AbstractTestCase
{
    public function testAge()
    {
        $birthday = date('Y-m-d', strtotime('-1 year -1 month  -1 day'));

        $this->assertEquals(1, age($birthday));
    }

    public function testUuid()
    {
        $uuid = uuid();
        // var_dump($uuid);
        $this->assertIsString($uuid);
    }

    public function testFilesizeFormat()
    {
        $str = filesizeFormat(1024 * 1022 * 10);
        // var_dump($str);
        $this->assertIsString($str);
        $this->assertEquals('9.98 MB', $str);
    }

    public function testArrayToTree()
    {
        $tree = arrayToTree([
            ['id' => 1, 'parent_id' => null],
            ['id' => 2, 'parent_id' => 1],
            ['id' => 3, 'parent_id' => 2],
            ['id' => 4, 'parent_id' => 1],
        ]);
        // var_dump($tree);
        $this->assertIsArray($tree);
        $this->assertEquals([
            [
                'id' => 1, 'parent_id' => null,
                'children' => [
                    [
                        'id' => 2, 'parent_id' => 1,
                        'children' => [
                            ['id' => 3, 'parent_id' => 2],
                        ],
                    ],
                    ['id' => 4, 'parent_id' => 1],
                ],
            ],
        ], $tree);
    }
}
