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
//批量订单上传
$result = $orderTrade->batchOrders("../crbank.com.cn/src/Config/REQ_102_56101013_20181210_000011.txt");
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
//    string(32) "REQ_102_56101013_20181210_000001"
//  }
//}

$result = $orderTrade->getOrderBatchResp('20181211', "REQ_102_56101013_20181210_000011");
var_dump($result);
//array(2) {
//    ["return_code"]=>
//  string(4) "FAIL"
//  ["return_msg"]=>
//  string(33) "该任务还在处理请稍等..."
//}