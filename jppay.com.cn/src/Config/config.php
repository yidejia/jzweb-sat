<?php


/**
 * 聚合支付相关配置
 * 注：该部分配置要特别注意保密
 */

return [
    'default_dsn' => "default",
    'dsn' => [
        'default' => [
            //平台商账号
            'mch_id' => '0',
            //账号
            'key' => "123sdfasdfasdfasdfsadf",
            //聚合支付请求地址
            'url' => "https://api.ulopay.com",
            //聚合支付服务器异步通知地址
            'notify_url' => "https://xxx.omc.cn/notify.php",
        ],
        'dev' => [
            //平台商账号
            'mch_id' => '1',
            //账号
            'key' => "123sdafasdfasdfasdf",
            //聚合支付请求地址
            'url' => "https://api.ulopay.com",
            //聚合支付服务器异步通知地址
            'notify_url' => "https://xxx.com.cn/notify.php",

        ],
        'test' => [
            //平台商账号
            'mch_id' => '2',
            //账号
            'key' => "123sdafasdfasdfasdf",
            //聚合支付请求地址
            'url' => "https://api.ulopay.com",
            //聚合支付服务器异步通知地址
            'notify_url' => "https://xxx.com.cn/notify.php",
        ],
        'prod' => [
            //平台商账号
            'mch_id' => '3',
            //账号
            'key' => "123sdafasdfasdfasdf",
            //聚合支付请求地址
            'url' => "https://api.ulopay.com",
            //聚合支付服务器异步通知地址
            'notify_url' => "https://xxx.com.cn/notify.php",
        ],
    ]
];