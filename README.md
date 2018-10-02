# 中国行政规划地址库

本项目主要分为三大部分。

- [SDK](#sdk)
- [脚本](#脚本)
- [数据](#数据)

## SDK

本项目数据来源为菜鸟物流官方接口，主要提供以下几个接口，足以满足日常地址应用。

### [地址选择联动](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-division)

> 四级地址录入选择服务，向用户提供标准结构化的全国地址数据，适用于前端地址选择控件等多种应用场景。

### [地址录入联想](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-associationcncloud-dzk-detail-associate)

> 地址录入联想服务，通过配置业务组件，帮助用户输入规范的地址信息，从源头上解决因地址混乱造成的物流链路流转问题。该服务依托权威标准的四、五级地址库，提供给用户规范的地址，用户可根据自己的输入习惯，通过选择POI或拼音输入联想等方法，录入自己想要的标准地址信息。

### [地址规范查询](https://cloud.cainiao.com/markets/cnwww/cncloud-dzk-detail-query)

> 五级地址查询服务基于权威五级地址库，可根据原始地址查询出标准五级地址，或对已有非标准地址进行清洗、结构化、纠错和补全。

## 脚本

根据以上接口，可通过爬虫得到完整的行政规划数据。

## 数据