<?php

include "../vendor/autoload.php";

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

//实例化
$orderTrade = new \jzweb\sat\crbank\Client($config);
//批量交易上传 ,没有响应文件的生成, 103的结果数据都在对账单文件里面
$result = $orderTrade->batchTrade("../crbank.com.cn/src/Config/REQ_103_56101013_20181210_000077.txt");
var_dump($result);
//array(3) {
//    ["return_code"]=>
//  string(7) "SUCCESS"
//  ["return_msg"]=>
//  string(6) "成功"
//  ["data"]=>
//  array(2) {
//        ["dir"]=>
//    string(8) "20181210"
//    ["file"]=>
//    string(32) "REQ_103_56101013_20181210_000001"
//  }
//}

