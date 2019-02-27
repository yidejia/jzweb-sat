<?php


/**
 * 银行相关配置
 * 注：该部分配置要特别注意保密
 */

return [
    'default_dsn' => "default",
    'dsn' => [
        'default' => [
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
            'private_key_path' => "private.key",
            //http服务器请求地址
            'url' => "http://fundsmgr.gateway.test.szulodev.com"
        ],
        'dev' => [
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
            'private_key_path' => "private.key",
            //http服务器请求地址
            'url' => "http://fundsmgr.gateway.test.szulodev.com"

        ],
        'test' => [
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
            'private_key_path' => "private.key",
            //http服务器请求地址
            'url' => "http://fundsmgr.gateway.test.szulodev.com"
        ],
        'prod' => [
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
            'private_key_path' => "private.key",
            //http服务器请求地址
            'url' => "http://fundsmgr.gateway.test.szulodev.com"
        ],
    ]
];