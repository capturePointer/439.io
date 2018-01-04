# 490.io （PHP实现短链接）

## Features
* 生成短链
* 生成短链二维码

## Install
* 安装三方组件
```shell
composer install
```
* 配置config
```
cd short_link/config
cp config_example.php config.php
# 在配置文件中设置数据库信息
``` 

* 导入数据库DDL
```shell
source init.ddl.sql
```

* Web服务配置
```
root short_link/public
index index.php
```

## What's Next
* 短链生成api
* 静态资源整理

## 第三方代码引用
* 前端模版：https://github.com/int64ago/302.at
* PHP框架：https://github.com/capturePointer/pureFrameworkPhp
* 短ID生成方案：https://github.com/YisonZhao/hash-id
* 二维码生成方案：https://github.com/endroid/qr-code