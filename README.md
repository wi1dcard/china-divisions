# 中国行政区划地址库 SDK + 爬虫 + 数据

本项目主要分为三大部分，如标题所示。

典型应用场景如：**选择省市区关联下级**、**输入文本地址转换为结构化地址**、**输入部分地址联想下级**等。

## [SDK](src/)

使用方法非常简单：

```php
use ChinaDivisions;

$division = new Division(1); // 参数为 DivisionID；1 代表中国，即根结点。

$division->self(); // 获取自身信息，返回键值数组
$division->children(); // 获取下一级子区划，返回 Division 对象数组
$division->parent(); // 获取上一级父区划，返回 Division 对象
$division->ancestors(); // 获取所有上级父区划，返回 Division 对象数组
$division->breadcrumb(); // 根据父区划拼接可读地址，返回字符串
$division->guess(); // 地址录入联想
$division->search(); // 地址规范查询
```

数据来源为淘宝菜鸟物流，目前主要封装如下接口，足以满足日常地址应用。

### [地址选择联动 | 上下级区划查询](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-division)

> 四级地址录入选择服务，向用户提供标准结构化的全国地址数据，适用于前端地址选择控件等多种应用场景。

### [地址录入联想 | 联想下级地址](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-associationcncloud-dzk-detail-associate)

> 地址录入联想服务，通过配置业务组件，帮助用户输入规范的地址信息，从源头上解决因地址混乱造成的物流链路流转问题。该服务依托权威标准的四、五级地址库，提供给用户规范的地址，用户可根据自己的输入习惯，通过选择 POI 或拼音输入联想等方法，录入自己想要的标准地址信息。

### [地址规范查询 | 文本地址规范化](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-query)

> 五级地址查询服务基于权威五级地址库，可根据原始地址查询出标准五级地址，或对已有非标准地址进行清洗、结构化、纠错和补全。

## [脚本](scripts/)

根据以上接口，可通过爬虫得到完整的区划数据。

使用方法：

```bash
composer install
php scripts/crawler.php
```

随后检查 `script/output` 目录即可。

亦可阅读 [爬虫源码](scripts/crawler.php)，自测两分钟左右即可生成一份区县级列表。

## [数据](scripts/output)

最终输出的数据如下，更新日期见 Git 提交历史。

- [四级行政区划（精确到区县）完整版](scripts/output/level4-full.json)。
- [四级行政区划（精确到区县）筛选版](scripts/output/level4-mini.json)，剔除已废弃的区划，并精简键名、规范键值类型。

## 协议

MIT

欢迎 Issue，欢迎 PR。

  