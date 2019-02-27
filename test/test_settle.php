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
$sdk = new \jzweb\sat\crbank\Client($config);
//批量结算上传
$result = $sdk->batchSettle("../crbank.com.cn/src/Config/REQ_104_56101013_20181210_000088.txt");
var_dump($result);


//查询批量结算结果
$result = $sdk->getBatSettleResp('20181210', "REQ_104_56101013_20181210_000088");
var_dump($result);
