<?php


/**
 * 龙存管
 * 注：该部分配置要特别注意保密
 */

return [
    'default_dsn' => "default",
    'dsn' => [
        'default' => [
            //版本
            'version' => '01',
            //字符编号 00:GBK
            'charSet' => '00',
            //验签方式
            'signType' => 'RSA',
            //数据格式，1:json,2:xml格式
            'dataType' => '2',
            //商户编号
            'PID' => '800020000010030',
            //Rsa公钥
            'public_key_path' => 'ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => 'ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => 'ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_url' => 'http://ccbll:38180/',
        ],
        'dev' => [
            //版本
            'version' => '01',
            //字符编号 00:GBK
            'charSet' => '00',
            //验签方式
            'signType' => 'RSA',
            //数据格式，1:json,2:xml格式
            'dataType' => '2',
            //商户编号
            'PID' => '800020000010030',
            //Rsa公钥
            'public_key_path' => 'ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => 'ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => 'ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_url' => 'http://ccbll:38180/',

        ],
        'test' => [
            //版本
            'version' => '01',
            //字符编号 00:GBK
            'charSet' => '00',
            //验签方式
            'signType' => 'RSA',
            //数据格式，1:json,2:xml格式
            'dataType' => '2',
            //商户编号
            'PID' => '800020000010030',
            //Rsa公钥
            'public_key_path' => 'ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => 'ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => 'ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_url' => 'http://ccbll:38180/',
        ],
        'prod' => [
            //版本
            'version' => '01',
            //字符编号 00:GBK
            'charSet' => '00',
            //验签方式
            'signType' => 'RSA',
            //数据格式，1:json,2:xml格式
            'dataType' => '2',
            //商户编号
            'PID' => '800020000010030',
            //Rsa公钥
            'public_key_path' => 'ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => 'ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => 'ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_url' => 'http://ccbll:38180/',
        ],
    ]
];