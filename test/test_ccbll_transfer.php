<?php

/**
 * 龙存管测试用例
 * 出入金类
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
 * 商户出金(H5)
 * 需要提前扣除oa保证金
 * 实时获取oa金额限制
 */
$data = [
    'tradeNo' => 'm2019092016014761149',
    'mbrCode' => '0030100000759343',
    'mercOrdNo' => 'wd2019092016014761149', //提现单号
    'trAmt' => 200,
    'trxType' => 19002, //19001:个人用户出金、19002:企业用户出金
    'urgentFlag' => 1, //加急标志，1:加急、2:正常，默认1:加急
    'feeType' => 0, //手续费收取模式，0:不收取、1:收取手续费，默认不收取
    'feeAmt' => 0, //平台手续费收入，单位分,默认为0
    'pageRetUrl' => 'http://106.75.132.218:80/api/test/index', //页面返回url
    'bgRetUrl' => 'http://106.75.132.218:80/api/test/notice',   //后台通知url
];
$result = $sdk->withdraw($data);
// $result = $sdk->withdraw($data, true); //移动端访问第二个参数为true
print_r($result);

/**
 * 平台入金
 * 手续费收入账户：下单退款的手续费的交互，
 * 手续费支出账户：支付渠道手续费的
 * 营销金账户：平台给商户的的一种优惠，主要就是涉及下单和退款的营销金收支。
 * 担保户：是为了买卖的安全设置的，只是个过渡账户。
 * 代收渠道手续费账户：存放给渠道的手续费
 */
$data = [
    'tradeNo' => 't2019092418041845145',
    'accTyp' => '02', //02:手续费支出电子登记簿，03:营销电子登记簿
    'mercOrdNo' => 't20190924165150',
    'trAmt' => 21, //测试环境线上入金需要0.2元/笔 手续费
    'remark' => '入金测试',
];
$result = $sdk->platTopUp($data);
print_r($result);

/*
array:2 [▼
  "info" => array:8 [▼
    "trxCode" => "300009"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "t2019092418041845145"
    "retCode" => "PWM00000"
    "errMsg" => "交易成功"
    "retTime" => "20190924151627"
    "salt" => "NO5E5C3be6+rQxv7arUuhw9a/WoqGYWdkjGWzhVhbp3pTLyyyMLbBCfSx9fzsk3wlGYc9sM6X5CmusJhdwyd4JI250O0W1oZXjcrdWpWeUHWt1oCaPqwWyJ/WhCphyF5cJrVANIj6Q3j/LnK05CAlm0dOCapOf2J ▶"
  ]
  "body" => array:5 [▼
    "rstCode" => "0"
    "rstMess" => "充值成功"
    "jrnno" => "180606180006214612"
    "mercOrdNo" => "t20190924165150"
    "balance" => "21"
  ]
]
 */

/**
 * 平台电子登记簿出金
 * 出金请求成功发起后，接口实时返回出金的受理结果，实际出金结果需通过单笔交易查询接口返回的查询信息为准，建议每15分钟查询一次
 */
$data = [
    'tradeNo' => 't2019092418082043252',
    'accTyp' => '01', //01:手续费收入电子登记簿，02:手续费支出电子登记簿，03:营销电子登记簿，04:代收渠道手续费电子登记簿
    'mercOrdNo' => 't20190924180630',
    'trAmt' => 2,
    'remark' => '出金测试',
];
$result = $sdk->platWithdraw($data);
print_r($result);

