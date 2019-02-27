<?php

/**
 * jzweb-sat集成测试
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2017/2/24
 */

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

$mch_no = "86101065";
$sdk = new \jzweb\sat\crbank\Client($config);

//获取商户详情
//$result = $sdk->mechQuery($mch_no);
//print_r($result); exit;
//获取商户账户详情
$result = $sdk->accountQuery($mch_no);
var_dump($result);
//注销账号
//$result = $sdk->cancelAccount($mch_no);
//var_dump($result);
exit;
//批量商户进件
$result = $sdk->mechBatchRegister("../crbank.com.cn/src/Config/REQ_100_56101013_20181210_130001.txt");
var_dump($result);
//查询批量商户进件处理进度
$result = $sdk->getBatchResp("20181210", "REQ_100_56101013_20181210_130001");
var_dump($result);
