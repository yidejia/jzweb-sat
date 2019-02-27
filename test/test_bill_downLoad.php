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
$last_day=date("Ymd",strtotime("-1 days"));
$fw_account = $config['fw'];
$result = $sdk->downLoadBillData("/".$last_day."/bill","BILL_".$fw_account."_".$last_day);
print_r($result);