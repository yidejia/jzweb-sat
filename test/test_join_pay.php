<?php

include "../vendor/autoload.php";


$config = [
    'mch_id' => '123123123',
    'key' => '123123123123123123',
    'url' => "https://api.ulopay.com",
    'notify_url' => 'https://xxx.com.cn'
];

$sdk = new \jzweb\sat\jppay\Client($config);
//微信公众号支付
$result = $sdk->weixinJsPay("wx2d1334fcb491aa09", "oMUcuuKKcnmfh6wu6wpc9lRVC8Bo", "d02010003001", 1);
print_r($result);
exit;
//微信扫码支付
//$result = $sdk->weixiNative("d02010002007", 100);
//print_r($result);
//exit;
//微信APP支付
//$result = $sdk->weixinAppPay2("d02010002003", 1);
//print_r($result);
//exit;
//微信H5支付
//$result = $sdk->weixinH5Pay("d02010002004", 1);
//print_r($result);
//exit;
//微信小程序支付
$result = $sdk->weixinMpPay("wx5d2eb35c8cf1c873", "oVqkD0SEk5a_jFuPB9vifRvuH5ao", "d02010002022", 1);
print_r($result);
exit;
//支付宝扫码支付
//$result = $sdk->alipayNative("d02010002005", 1);
//print_r($result);
//exit;
//支付宝H5支付
$result = $sdk->alipayH5Pay("d02010002018", 1);
print_r($result);
exit;
//银联扫码支付
$result = $sdk->unionpayNative("d02010003021", 1);
print_r($result);
exit;
//订单查询
//$result = $sdk->orderQuery("d0201000003");
//print_r($result);
//exit;
//订单退款
//$result = $sdk->orderRefund("d02010002007", "d0201000007", 100, 50);
//print_r($result);
//exit;
//退款查询
$result = $sdk->orderRefundQuery("d02010002007");
print_r($result);


