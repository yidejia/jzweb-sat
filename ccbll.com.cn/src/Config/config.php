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
            'public_key_path' => DATA_DIR . 'ccbll/ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => DATA_DIR . 'ccbll/ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => DATA_DIR . 'ccbll/ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_pay_url' => 'http://106.75.132.218/ccbpay/notify_pay.php',
            //回调地址
            'callback_create_account_url' => 'http://106.75.132.218/ccbpay/notify_create_account.php',
            //回调地址
            'callback_update_account_url' => 'http://106.75.132.218/ccbpay/notify_update_account.php',
            //回调地址
            'callback_rest_pwd_url' => 'http://106.75.132.218/ccbpay/notify_rest_pay.php',
            //回调地址
            'callback_user_confirm_url' => 'http://106.75.132.218/ccbpay/notify_user_confirm.php',
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
            'public_key_path' => DATA_DIR . 'ccbll/ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => DATA_DIR . 'ccbll/ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => DATA_DIR . 'ccbll/ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_pay_url' => 'http://106.75.132.218/ccbpay/notify_pay.php',
            //回调地址
            'callback_create_account_url' => 'http://106.75.132.218/ccbpay/notify_create_account.php',
            //回调地址
            'callback_update_account_url' => 'http://106.75.132.218/ccbpay/notify_update_account.php',
            //回调地址
            'callback_rest_pwd_url' => 'http://106.75.132.218/ccbpay/notify_rest_pay.php',
            //回调地址
            'callback_user_confirm_url' => 'http://106.75.132.218/ccbpay/notify_user_confirm.php',

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
            'public_key_path' => DATA_DIR . 'ccbll/ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => DATA_DIR . 'ccbll/ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => DATA_DIR . 'ccbll/ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_pay_url' => 'http://106.75.132.218/ccbpay/notify_pay.php',
            //回调地址
            'callback_create_account_url' => 'http://106.75.132.218/ccbpay/notify_create_account.php',
            //回调地址
            'callback_update_account_url' => 'http://106.75.132.218/ccbpay/notify_update_account.php',
            //回调地址
            'callback_rest_pwd_url' => 'http://106.75.132.218/ccbpay/notify_rest_pay.php',
            //回调地址
            'callback_user_confirm_url' => 'http://106.75.132.218/ccbpay/notify_user_confirm.php',
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
            'public_key_path' => DATA_DIR . 'ccbll/ccbll_public_key.pem',
            //Rsa私钥
            'private_key_path' => DATA_DIR . 'ccbll/ccbll_private_key.p12',
            //Rsa私钥密码
            'private_key_keyword_path' => DATA_DIR . 'ccbll/ccbll_private_key_keyword.txt',
            //api请求地址
            'api_url' => 'http://58.249.123.36:38180',
            //h5请求地址
            // 'h5_url' => 'http://56.0.98.230:38180',
            'h5_url' => 'https://lcg.murongtech.com',
            //地址Query
            'url_query' => '/bhdep/payTransactionNew',
            //回调地址
            'callback_pay_url' => 'http://106.75.132.218/ccbpay/notify_pay.php',
            //回调地址
            'callback_create_account_url' => 'http://106.75.132.218/ccbpay/notify_create_account.php',
            //回调地址
            'callback_update_account_url' => 'http://106.75.132.218/ccbpay/notify_update_account.php',
            //回调地址
            'callback_rest_pwd_url' => 'http://106.75.132.218/ccbpay/notify_rest_pay.php',
            //回调地址
            'callback_user_confirm_url' => 'http://106.75.132.218/ccbpay/notify_user_confirm.php',
        ],
    ]
];