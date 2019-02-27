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


$sdk = new \jzweb\sat\crbank\Client($config);

$data['mch_no']='86101099';
$data['divide_plat_no']='56101013';
$data['divide_mch_no']='86101099';
$data['out_trade_no']='d4898934831212';
$data['transaction_id']='87487348224384309';
$data['trade_money']=100;
$data['refund_money']=0;
$data['trade_state']=2;
$data['proce_fee']=1;
$data['trade_time']='20181213175010';
$data['pay_time']='20181213175200';
$data['total_fee']=100;
$data['divide_plat_amount']=50;
$data['divide_mch_amount']=50;
$data['currency']='CNY';
$data['refund_transaction_id']='';
$data['out_refund_trade_no']='';
$data['out_order_no']='';
$data['out_refund_no']='';

$result = $sdk->adjust04($data);
print_r($result);