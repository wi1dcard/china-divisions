# 中国行政区划地址库 SDK + 爬虫 + 数据

📍 本项目主要分为三大部分，如标题所示；数据来源为 **淘宝** 菜鸟物流，相比其它同类项目更加 **真实准确**，且包含 **港澳台**。

🤔 典型应用场景如：**选择省市区关联下级**、**输入文本地址转换为结构化地址**、**输入部分地址联想下级**等。

[![Build Status](https://travis-ci.org/wi1dcard/china-divisions.svg?branch=master)](https://travis-ci.org/wi1dcard/china-divisions)
[![Coverage Status](https://coveralls.io/repos/github/wi1dcard/china-divisions/badge.svg)](https://coveralls.io/github/wi1dcard/china-divisions)
[![StyleCI](https://github.styleci.io/repos/151451370/shield?branch=master)](https://github.styleci.io/repos/151451370)
[![Packagist](https://img.shields.io/packagist/v/wi1dcard/china-divisions.svg)](https://packagist.org/packages/wi1dcard/china-divisions)

## [SDK](src/)

使用方法十分简单，首先使用 Composer 安装。

```bash
composer require wi1dcard/china-divisions
```

方法概览如下：

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

目前主要支持如下接口，基本满足多数地址应用。

### [地址选择联动 | 上下级区划查询](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-division)

> 四级地址录入选择服务，向用户提供标准结构化的全国地址数据，适用于前端地址选择控件等多种应用场景。

### [地址录入联想 | 联想下级地址](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-associationcncloud-dzk-detail-associate)

> 地址录入联想服务，通过配置业务组件，帮助用户输入规范的地址信息，从源头上解决因地址混乱造成的物流链路流转问题。该服务依托权威标准的四、五级地址库，提供给用户规范的地址，用户可根据自己的输入习惯，通过选择 POI 或拼音输入联想等方法，录入自己想要的标准地址信息。

### [地址规范查询 | 文本地址规范化](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-query)

> 五级地址查询服务基于权威五级地址库，可根据原始地址查询出标准五级地址，或对已有非标准地址进行清洗、结构化、纠错和补全。

## [爬虫](scripts/)

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

## Why This

GitHub 上搜索关键词「地区」、「省市区」、「行政区划」等关键词有大量的仓库，但或多或少存在以下问题：

- 数据陈旧，缺乏更新；
- 数据格式不合适；
- 缺少村级、街道级数据；
- 基于国标，缺少港澳台数据；

而基于本项目，你可以随时使用爬虫获得最新区划，甚至是使用 SDK 实现抓取并输出属于自己的五级地址库。

其次，国内电商巨头阿里巴巴拥有大量物流、派送体系资源，同时线上业务也有对此的依赖需求；因此我认为其数据质量和更新速度或许不比国标文件差，甚至有可能优于后者，故本项目选用菜鸟物流 API 作为数据源。

## 声明

本仓库源代码基于 MIT 协议发布，数据版权属于阿里。
