<?php

/**
 * 龙存管测试用例
 * @author changge
 */

include "../vendor/autoload.php";

$config = [
    //调试模式
    'debug' => true,
    //版本
    'version' => '01',
    //字符编号 00:GBK
    'charSet' => '00',
    //验签方式
    'signType' => 'RSA',
    //数据格式，1:json,2:xml格式
    'dataType' => '1',
    //支付渠道
    'payChannel' => 'CCB', //CCB:建行;WXPAY:微信支付
    //商户编号
    'PID' => '800020000010030',
    //微信开放平台应用id
    //支付密钥
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
    'callback_pay_url' => 'http://106.75.132.218/jppay/notify2.php',
    //日志文件
    'log_file_path' => "ccbll_access_" . date('Ym') . ".log",
    //日志路径
    'log_path' => date('Ym') . "/",
];

$client = new \jzweb\sat\ccbpay\Client($config);

$body = [
    "mch_no" => "0009100000119128",
    "goods_str" => "32684",
    "goods_ids" => "32684",
    "count" => "1",
    "mobile" => "18675794664",
    "plat_rate" => "0.106",
    "mch_rate" => "",
    "partner_no" => "",
    "partner_rate" => "",
    "plat_mrk_fee" => 0,
    "plat_fee" => 6.78,
    "mch_fee" => 57.22,
    "partner_fee" => 0,
];

$result = $client->weixinAppPay('t2019091717094397988', 'd0110919091818', 6400, $body);
print_r($result);
die;

$sdk = new \jzweb\sat\ccbll\Client($config);

/**
 * 匿名支付
 */
$data = [
    'tradeNo' => 't2019091717094397987',
    'payType' => 'C', //C:微信APP支付、D:支付宝APP支付、H:一码付（H5二维码）、I:微信扫码支付、J:微信公众号支付、K:建行信用卡分期支付、L:银联在线、M:建行网关对公、N:建行网关对私、P:支付宝扫码、Q:一码付（自定义二维码）、R:龙支付扫码、S:龙支付APP支付
    'mercOrdNo' => 'd0110919091818', //总订单号
    'trxType' => '12001', //业务类型包括：12001:B2C商城消费、12002:B2C商城消费合伙人模式、12006:B2B商城消费、12007:B2B商城消费合伙人模式
    'trAmt' => 2, //交易金额
    'tradt' => date('Ymd'), //交易日期
    'tratm' => date('His'), //交易时间
    'pageRetUrl' => 'http://106.75.132.218:80/api/test/index', //页面返回url
    'bgRetUrl' => 'http://106.75.132.218:80/api/test/notice', //后台通知url
    'ccy' => 'CNY',
    'platFeeAmt' => 1, //总平台手续费
    'prdSumAmt' => 2, //总金额
    'cnt' => 1, //子订单笔数,最大为20笔
    'Lists' => [ //子订单列表
        [
            'tradeOrdNo' => 'd0110919091817',
            'mercMbrCode' => '0030100000759343',
            'tradeNm' => 'HC01益生菌洁护牙膏',
            'tradeRmk' => '1001', //填产品ID
            'tradeNum' => 1,
            'tradeAmt' => 2,
            'platFeeAmt1' => 1,
            'cMbl' => '13422318178', //不填无法进行确认收货
        ],
    ],
    'ordValTmUnit' => 'H', //订单有效时间单位,D:日、H:时、M:分、S:秒
    'ordValTmCnt' => 2, //订单有效时间数量
    'sumExpressAmt' => 0, //商家总运费,单位分，默认为0
    'sumInsuranceAmt' => 0, //总保险费,单位分，默认为0
    'cntlist1' => 0, //商家运费笔数，默认为0
    'Lists1' => [],
];

$result = $sdk->anonyPay($data);
print_r($result);
die;
/*
[
"info" => [
"trxCode" => "200009"
"version" => "01"
"dataType" => "1"
"reqSn" => "t2019091717094397987"
"retCode" => "MCG00000"
"errMsg" => "交易成功"
"retTime" => "20190917170956"
"salt" => "qhMAVJmhBUEmVPFK3DufwkmiFJWMJBMVl7csMxWGqR7wWlz1HoibWrsJMlWDyGwgiT6bJ6rxUAIbzKG2ManOG8ifpajhSoJeIRJAadnWyM4VOytT5QbjvTLejq51WUyRjIMGC/3dxv7FsvTa9bELXB70MZ1HdQY1 ▶"
]
"body" => [
"rstCode" => "0"
"rstMess" => "交易成功"
"mercOrdNo" => "d0110919091705"
"payTyp" => "I"
"mercOrdMsg" => "https%3A%2F%2Fibsbjstar.ccb.com.cn%2FCCBIS%2FQR%3FQRCODE%3DCCB9980011898915813365402"  //urldecode之后用来生产支付二维码
"jrnno" => "180606130006170045" //交易订单号
"trAmt" => "900"
"tradt" => "20190917"
"tratm" => "170949"
"payChannel" => ""
]
]
 */

/**
 * 商品发货通知
 */
$data = [
    'tradeNo' => 't2019091816075657635',
    'mbrCode' => '0030100000759343',
    'trxType' => '12003', //12003:商城收货确认
    'oriOrdNo' => 'd0110919091817', //原订单编号
    'tradeOrdNo' => 'd0110919091817', //子商品订单编号
];
$result = $sdk->goodsNotice($data);
print_r($result);
/*
[
"info" => [
"trxCode" => "200004"
"version" => "01"
"dataType" => "1"
"reqSn" => "t2019091816075657635"
"retCode" => "MCG00000"
"errMsg" => "交易成功"
"retTime" => "20190918160810"
"salt" => "BSYSq0ToSIEyNebv0JprA2qQ1PQKHEqeXXhWxD3EiWzEBv9Q2ra+WnNECkMjy2MnzJZcjTlgN3FtZ8lG3c/bfZUE9HeMrWcnSKab3YYmlyAjge5ivwB4i33xrUhm5Npp47wXRM03n+ddGiHmWDudQJ66PM5FzgnG ▶"
]
"body" => [
"rstCode" => "0"
"rstMess" => "交易成功"
"jrnno" => "180606110006175824"
]
]
 */

/**
 * 退款申请
 * 注意：如果不填手续费，那么手续费将由商户承担
 */
$data = [
    'tradeNo' => 't2019091718173156418',
    'refundOrdNo' => 'th0110919091817',
    'trxType' => '12008', //12008:商品退款，12014:佣金退款
    'operType' => '21', //针对子订单，子订单退全额就是全额退款
    'oriOrdNo' => 'd0110919091817',
    'oriOrdAmt' => 2,
    'refundDt' => date('Ymd'),
    'refundTm' => date('His'),
    'tradeOrdNo' => 'd0110919091817',
    'tradeNm' => 'HC01益生菌洁护牙膏',
    'tradeRmk' => 'HC01益生菌洁护牙膏',
    'tradeNum' => 1,
    'tradeAmt' => 2,
    'feeAmt' => 1, //填了手续费由平台承担，不填有商户承担
    'platFeeAmt1' => 1, //填了手续费由平台承担，不填有商户承担
    'remark' => '退款测试',
];

$result = $sdk->refund($data);
print_r($result);
/*
[
"info" => [
"trxCode" => "200007"
"version" => "01"
"dataType" => "1"
"reqSn" => "t2019091718173156418"
"retCode" => "MCG00000"
"errMsg" => "交易成功"
"retTime" => "20190917181743"
"salt" => "nx4dHWxxIwpVTiw9TD+PQXnZonl/CQeVRlStk7ryr9kbL64jNY+wn4e8McBRGKkmnrWAM+G9vFSmMuUp/eugJk4bFNQEQZm7jS1UV06ggYidJbYHwNLkZTCjNn30GgaTSZgYjPsV10pvPWJ4fVvlycNSinR/4bLs ▶"
]
"body" => [
"rstCode" => "0"
"rstMess" => "交易成功"
"operType" => "21"
"tradeOrdNo" => "d0110919091705"
"tradeNm" => "HC01益生菌洁护牙膏"
"tradeRmk" => "HC01益生菌洁护牙膏"
"tradeNum" => "1"
"tradeAmt" => "900"
"oriOrdAmt" => "900"
"feeAmt" => "0"
"expressAmt" => ""
"insuranceAmt" => ""
"platMrkAmt1" => ""
"servAmt" => ""
"platFeeAmt1" => "0"
"remark" => "退款测试"
"refundDt" => "20190917"
"refundTm" => "181731"
"jrnno" => "180606110006170648"
]
]
 */

/**
 * 用户确认
 */
$data = [
    'tradeNo' => 't2019091718173156418',
    'mbrCode' => '0030100000759343',
    'trxType' => '12008', //12003:商城收货确认，12008:商品退款，12014:佣金退款
    'jrnno' => '180606150006176605', //如果是收货确认，用发货通知的交易单号，如果是退款，用退款申请的交易单号
    'agreest' => 'Y', //交易确认状态,Y:同意、N:不同意/延迟确认
    'pageRetUrl' => 'http://106.75.132.218:80/api/test/index', //页面返回url
    'bgRetUrl' => 'http://106.75.132.218:80/api/test/notice', //后台通知url
];

$result = $sdk->userToConfirm($data);
// $result = $sdk->userToConfirm($data, true); //移动端第二参数为true
print_r($result);

/**
 * 超时后平台代为确认收货
 * 发货通知3天后可操作
 */
$data = [
    'tradeNo' => 't2019092310260012861',
    'mbrCode' => '0030100000759343',
    'trxType' => '12010', //12010:平台确认收货
    'jrnno' => '180606110006175824',
    'ordNo' => 'd0110919091815',
    'traAmt' => 2,
    'tradt' => date('Ymd'),
    'tratm' => date('His'),
];
$result = $sdk->insteadToConfirm($data);
print_r($result);
/*
array:2 [▼
"info" => array:8 [▼
"trxCode" => "200008"
"version" => "01"
"dataType" => "1"
"reqSn" => "t2019092310260012861"
"retCode" => "MCG00000"
"errMsg" => "交易成功"
"retTime" => "20190923102623"
"salt" => "gm5pBm2o0lOpViCOE7Qjwm8jYamGifwvKxmd0WoZp7VtKZ5TZ6SuRjQtYH4gQRLpMPZVsbmYob7LyfnegKOgmi0PpiTGJ+P882qlhvcpU1fArFWSBEUtp6Va/AuECu2SnClHiHHAKi4qA+xTMgiKjfDgh8y/eto9 ▶"
]
"body" => array:7 [▼
"rstCode" => "0"
"rstMess" => "交易成功"
"trxType" => "12010"
"traAmt" => "2"
"tradt" => "20190923"
"tratm" => "102600"
"jrnno" => "180606110006175824"
]
]
 */
