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
$data['plat_no'] = '56101013';
$data['mch_no']='86101065';
// 自有平台生成的唯一单号
$data['out_trade_no']='txdh'.date('YmdHis');
// 银行卡
$data['bankcard']='4551111111111111144';
// 法人身份证
$data['idcard']='420222199208164498';
// 法人真实姓名
$data['realname']='Cheney';
// 法人联系手机
$data['mobile']='13202018503';
//鉴权, 上面的请求数据拿的就是进件审核成功的信息
//注意,鉴权接口对方直接走正式环境,所以需要却好身份证,银行卡和法人姓名是真实存在且一一对应的
$result = $sdk->startAuth($data);
print_r($result);
//Array
//(
//    [err_code] => AUTH_NAME_IS_NOT_EQUAL
//    [result_code] => FAIL
//    [err_code_des] => 鉴权姓名与结算信息不符合
//    [return_code] => SUCCESS
//)
