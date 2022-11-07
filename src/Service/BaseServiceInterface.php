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
namespace FirecmsExt\Utils\Service;

interface BaseServiceInterface
{
    public function listTable(array $params): array;

    public function treeTable(array $params): array;

    public function options(array $params, ?array $columns = ['id', 'title', 'enable'], ?array $sort = ['sort' => 'asc']): array;

    public function treeOptions(array $params, ?array $columns = ['id', 'parent_id', 'title', 'enable'], ?array $sort = ['sort' => 'asc']): array;

    public function store(array $params): array;

    public function show(string $id): array;

    public function update(array $params, string $id): array;

    public function destroy(string $ids): array;

    public function forceDestroy(string $ids): array;

    public function restore(string $ids): array;

    public function read(array $params): array;

    public function clear(array $params): array;

    public function forceClear(array $params): array;

    public function clearEmpty(?array $params = []): array;

    public function forceClearEmpty(?array $params = []): array;

    public function top(array $params, string $id): array;

    public function sort(array $params, string $id): array;

    public function display(array $params, string $ids): array;

    public function enable(array $params, string $ids): array;

    public function recommend(array $params, string $ids): array;

    public function state(array $params, string $ids): array;

    public function unusual(array $params, string $ids): array;

    public function hot(array $params, string $ids): array;

    public function directly(array $params, string $ids): array;

    public function publish(array $params, string $ids): array;

    public function publishExpired(array $params, string $ids): array;
}
