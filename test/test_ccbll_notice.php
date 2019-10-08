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
$data = ['INFO' => 'eyJ0cnhDb2RlIjoiMjAwMDA5IiwidmVyc2lvbiI6IjAxIiwiZGF0YVR5cGUiOiJqc29uIiwicmVxU24iOiJ0MjAxOTA5MzAxNTExMzYzNjg1NCIsInJldENvZGUiOiJNQ0cwMDAwMCIsImVyck1zZyI6IlNVQ0NFU1MiLCJyZXRUaW1lIjoiMjAxOTA5MzAxNTEzMDAifQ%3D%3D',
    'BODY' => 'eyJyc3RDb2RlIjoiMCIsInJzdE1lc3MiOiJTVUNDRVNTIiwibWVyY09yZE5vIjoiZDAxMTA5MTkwOTMwMTUiLCJwYXlQZXJOYW1lIjoiKiIsInB1clBlck5hbWUiOiIqIiwicGF5VHlwIjoiUCIsImJhbmtDYXJkVHlwZSI6IiIsInVzcm1zZ05hbWUiOiIiLCJ1c3Jtc2dBY2NObyI6IiIsInVzckluZm9JZCI6IiIsInVzckluZm9NYmwiOiIiLCJqcm5ubyI6IjE4MDYwNjE1MDAwNjI5MzgzMiIsInRyQW10IjoiMSIsInRyYWR0IjoiMjAxOTA5MzAiLCJ0cmF0bSI6IjE1MTEzNiIsImN1cnJBbXQiOiIxIiwicGF5bXRBbXQiOiIxIiwib3ZwbEFtdCI6IjAiLCJwbGF0TXJrQW10IjoiMCIsIm1lcmNNcmtTdW1BbXQiOiIwIiwiY2N5IjoiQ05ZIiwiY250IjoiMSIsIkxpc3RzIjpbeyJ0cmFkZU9yZE5vIjoiZDAxMTA5MTkwOTMwMTUiLCJtZXJjTWJyQ29kZSI6IjAwMzAxMDAwMDA3NTkzNDMiLCJ0cmFkZU5tIjoiSEMwMSDS5sn6vvq94Luk0cC44CxBTTA5IMuuuNDH4bGht8DJucuqU1BGMzBQQSAgLEJDMDQgy67R%2BrGjyqqyz8u%2Fw%2BbEpCxCQzIzIMOrv9fPuNbC0N67pMPmxKQuLi61yCIsInRyYWRlUm1rIjoiMTAwMSwxMDAyLDEwMDAzLDEwMDA0Li4utcgiLCJ0cmFkZU51bSI6IjEwIiwidHJhZGVBbXQiOiIxIiwic2VydkFtdCI6IjAiLCJtZXJjTXJrQW10IjoiMCIsInBhcnRuZXJObSI6IiIsInBheW10VHJhZGVBbXQiOiIxIiwidW5QYXlUcmFkZUFtdCI6IjAifV0sInN1bUV4cHJlc3NBbXQiOiIwIiwic3VtSW5zdXJhbmNlQW10IjoiMCIsImNudGxpc3QxIjoiMCIsInJlbWsiOiIifQ%3D%3D',
    'SIGN' => 'eyJzaWduZWRNc2ciOiI0MEJFNjFEMThENEQyNjUyQTY0OEZERDI5QkNBMTcyMzMyM0Q2NkQ0QjUwMTY5RDRDNzhFQUU2RTM4NkU2RTgwODAzRDc0NTNEMDU5M0IwQTU1MTY2RDU2ODA1NEMzRjYyNjdDNzExMDZFQ0YzREQxNkJGNUYzNzc1MkVBMTRDQkRGRUYzNTZCNUFGRTJDQzNEN0ZCRUE3REFGRTc0QTE4ODQ4RTNCQTc2RDcyREQwOERENUYzMDFDMzdCODlDMTA2NkUyODkwNUM3OEFGMjA1Mzc0QTY0NUU1RTU1MEZCNUUyQUJFMTg0NUJCQkQxQjEwNDMzOTMwQjg0OUM0MUNDNURBN0NCQjVENkM0Q0JGMEY2MzlDMjFDNDlBNjdEMjQyM0JDRDY2OTBBREJBRTFBOEUyNkM2MDBFNDA0NTlGQ0M0QTM5OUE2OEI4MTRGMjY4OUUyNUU5QUUzRjU3QkE1NTdGQzJCMkU0OTc5MDM5MjIwQjJDMkU0QUJEQUZFMTlBN0NCNjhFQTMxNUU5ODZEQ0E1RTRDMDhEMDRGOTI0MDM2OEIyNzUyMTU2RjMyMUFDOUQ1NTc4MTFDODQxNDdCOTVENjFDNUI4NjAzNDNCMTA3QjI0OUZFQjNCREI2MzMwN0UxQ0Q2QkFDN0NEMENEQkM0Q0FCOTEwNUZCQkREMiJ9',
    'CONTENTTYPE' => 'json',];  //所有post过来的数据
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
