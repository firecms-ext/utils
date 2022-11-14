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

use Hyperf\Validation\Rule;

/**
 * 查询请求基类.
 */
abstract class QueryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'top' => 'nullable|boolean', // 置顶
            'read' => 'nullable|boolean', // 已读
            'repeal' => 'nullable|boolean', // 撤销
            'display' => 'nullable|boolean', // 显示
            'show' => 'nullable|boolean', // 显示
            'draft' => 'nullable|boolean', // 草稿
            'publish' => 'nullable|boolean', // 发布
            'recommend' => 'nullable|boolean', // 推荐
            'enable' => 'nullable|boolean', // 启用
            'unusual' => 'nullable|boolean', // 异常
            // 下拉选项
            'parent_id' => 'nullable|integer', // 上级
            'category_id' => 'nullable|integer', // 分类
            // 基础通用
            'recycle' => 'nullable|boolean', // 回收站
            'keyword' => 'nullable|max:100', // 关键字
            'perpage' => 'nullable|integer|between:1,1000', // 分页大小
            'pageSize' => 'nullable|integer|between:1,1000', // 分页大小
            'page' => 'nullable|integer|min:1', // 当前页码
            'field' => [
                'nullable',
                Rule::in([
                    'id',
                    'sort',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]),
            ], // 排序字段
            'order' => 'nullable|in:ascend,descend,asc,desc', // 排序方式
        ];
    }

    protected function only($array): array
    {
        $items = [];
        foreach (self::rules() as $key => $rule) {
            if (in_array($key, $array)) {
                $items[$key] = $rule;
            }
        }

        return $items;
    }
}
