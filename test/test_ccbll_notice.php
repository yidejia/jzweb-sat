<?php

/**
 * 龙存管测试用例
 * 通知类
 * @author changge
 */

include "../vendor/autoload.php";

$config = [
    //版本
    'version' => '01',
    //字符编号 00:GBK
    'charSet' => '00',
    //验签方式
    'signType' => 'RSA',
    //数据格式，1:json,2:xml格式
    'dataType' => '1',
    //商户编号
    'PID' => '800020000010030',
    //Rsa公钥
    'public_key_path' => '../ccbll.com.cn/src/Config/ccbll_public_key.pem',
    //Rsa私钥
    'private_key_path' => '../ccbll.com.cn/src/Config/ccbll_private_key.p12',
    //Rsa私钥密码
    'private_key_keyword_path' => '../ccbll.com.cn/src/Config/ccbll_private_key_keyword.txt',
    //api请求地址
    'api_url' => 'http://58.249.123.36:38180',
    //h5请求地址
    'h5_url' => 'http://56.0.98.230:38180',
    //地址Query
    'url_query' => '/bhdep/payTransactionNew',
    //回调地址
    'callback_url' => 'http://ccbll:38180/',
    //本地资源地址
    'base_url' => 'http://106.75.132.218:80/',
];

$sdk = new \jzweb\sat\ccbll\Client($config);

/**
 * 异步通知
 * 在后台通知url下调用, 用来接收异步通知结果
 * 'bgRetUrl' => 'http://106.75.132.218:80/api/test/notice',   //后台通知url
 * 接收成功 => 直接返回字符串SUCCESS
 * @return 'SUCCESS'
 */
$data = [];  //所有post过来的数据
$result = $sdk->asynchroNotice($data);
print_r($result);
/*
[
    'info' =>
    array (
    'trxCode' => '110004',
    'version' => '01',
    'dataType' => 'json',
    'reqSn' => 'm2019090918171942218',
    'retCode' => 'MCA00000',
    'errMsg' => 'SUCCESS',
    'retTime' => '20190909185032',
    ),
    'body' =>
    array (
    'rstCode' => '0',
    'rstMess' => 'SUCCESS',
    'jrnno' => '180606140006126231',
    'userSts' => '0',
    'accountNm' => '大**',
    'account' => '9876************3210',
    'bankNm' => '中国银行',
    'mbrCode' => '0030100000759343',
    ),
]
*/
