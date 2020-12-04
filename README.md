# 版本说明 php5.4 + php5.5.0

> 此library依赖guzzlehttp库
>
> 目前master分支要求php>=7.1，默认安装该分支
>
> 日志使用Monolog

# 安装 

## 1.修改composer配置（项目）将镜像地址指向国内代理

>```
>composer config repo.packagist composer https://packagist.composer-proxy.org
>```

## 2.上述命令执行成功后会看到以下变化

>```
>"repositories": {
>      "packagist": {
>          "type": "composer",
>          "url": "https://packagist.composer-proxy.org"
>      }
>  }
>```

## 3.安装jzweb/sat
```
composer require jzweb/sat
```


# jzweb/sat 使用示例
```
<?php
$config = [
    //平台商账号
    'fw' => '56101013',
    //账号
    'key' => "56101013",
    //密码
    'secret' => "YDJ50000001",
    //sftp主机IP
    'host' => "218.17.233.181",
    //sftp主机Port
    'port' => 20852,
    //Rsa私钥
    'private_key_path' => "../crbank.com.cn/src/Config/rsa_private_key.pem",
    //http服务器请求地址
    'url' => "http://fundsmgr.gateway.test.szulodev.com"
];

$mch_no = "86101065";
$sdk = new \jzweb\sat\crbank\client($config);

```

------

# 问题（git 提交vendor目录至项目）

* 如果当前开发的项目中包含vender目录，安装后提交代码，发现版本库中并没有jzweb/sdk的代码文件
* 出现这种情况后，马上去服务器查看，发现也没有，是什么问题？
* 仔细查阅了一些文档，发现是因为该安装包包含.git的缘故，于是可这样操作
* 1.vendor目录已经存在

    ```
    如果已经执行了composer update/install，需要先删除vendor目录 执行：rm -rf vendor
    git add -A
    git commit -m "remove vendor"
    composer update --prefer-dist
    git add . -A 
    git commit -m "recover vendor"
    ```
* 2.vendor目录不存在
    
    ```
    composer update --prefer-dist
    git add . -A 
    git commit -m "recover vendor"
    ```
* Notice: composer update --prefer-dist 优先从缓存取，不携带组件内的.git目录。
* 对于稳定版本 compose默认采用--prefer-dist模式安装
* --optimize-autoloader (-o): 转换 PSR-0/4 autoloading 到 classmap 可以获得更快的加载支持。特别是在生产环境下建议这么做，但由于运行需要一些时间，因此并没有作为默认值。

# 对接流程

 ```
     1. 使用单个请求或批量文件上传的方式去处理商户注册,商户注册结果可通过查询接口进行请求(平台商本身维护一份数据以后就不必频繁去查询)
     2.平台商在T+1日01:00-06:30时间段内上传订单数据[订单单号是支付单号,不是平台商的生成的单号,订单状态2交易 4退款,此外订单日期要传包含时分秒的,文档那里写错了],之后上传交易数据(银行明细和初步分账,目前允许有3个分账商户,后期会扩展到8个),入库再到入账,入账成功的会在后台的交易订单管理模块看到.
     3.分账宝在t+1日07：30生成账单,之后平台商自行下载账单数据,对有差异的数据通过补/调账接口进行补调账
     4.平台商在t+1日10:30之后上传结算数据(结算数据仅包含子商户结算数据即可).结算之后资金会进入各结算商户的可用余额户
     5.子商户提现之前,先通过鉴权操作,鉴权通过之后即可通过批量提现接口进行提现
     6.其他注意事项: 所有金额都是以 分 作为单位的
 ```


