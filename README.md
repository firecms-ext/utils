# firecms-ext/utils

```shell
# 依赖安装
composer require firecms-ext/utils
```

# 函数列表

```php
# Cache 对象
function cache(): CacheInterface;

# Redis 对象
function redis(): RedisProxy;

# 生成主键
function generateId(): string;

# 处理模型数据
function handleModelData(array $data): array;

# 计算年龄
function age(string $birthday): int;

# 生成 uuid 
function uuid(string $prefix = ''): string;

# 文件大小格式化
function filesizeFormat(int $filesize): string;

# array 转 tree
function toTree(array $rows, string $pid = 'parent_id', string $id = 'id', string $children = 'children'): array;

# 普通 tree 转 tree 下拉选项
function treeToOptions(array $tree, string $label = 'title', string $value = 'id', string $children = 'children'): array;

# 下拉选项
function options(array $rows, ?string $label = 'title', ?string $value = 'id'): array;

# 分组下拉选项
function groupOptions(array $groups, ?string $label = 'title', ?string $value = 'id'): array;

```