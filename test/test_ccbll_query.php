<?php

/**
 * 龙存管测试用例
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
 * 用户信息查询
 */
$data = [
    'tradeNo' => 'q2019091818144023699',
    'platCusNO' => '1109',
];

$result = $sdk->userInfoQuery($data);
print_r($result);
/*
[
  "info" => [
    "trxCode" => "400001"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "q2019091009500206495"
    "retCode" => "MCG00000"
    "errMsg" => "交易成功"
    "retTime" => "20190910095046"
    "salt" => "Ic0SF2EfwQOVylfdDN3/JcQ8mUfPIfG1VkgrNDl/s2TrycPt35sHDs1j0U9iTYH2YvEf6PYmdPxmG7OAqt4OWDsgO1Tp58IUIiLPv1dEM1Rj3+ntw7Z+Ynff87dquyqZ6uY+88JrsFaf/wE7oKADw4Z8qOUnFjlmDoh0Hofk9k0="
  ]
  "body" => [
    "mbrCode" => "0030100000759343"
    "rstCode" => "0"
    "rstMess" => "交易成功"
    "userSts" => "0"
    "platRoleID" => "100"
    "psCrpFlg" => "1"
    "payAgrNo" => "180606180006125994"
    "realNm" => "**部"
    "idType" => ""
    "idNo" => "9876***4321"
    "account" => "9876***3210"
    "bankMbl" => "0"
    "bankNm" => "中国银行"
    "busScope" => ""
    "legalPerNm" => "**国"
    "legalPerIdTyp" => ""
    "legalPerIdNo" => "2d1a37***3072"
    "busRegCapital" => ""
    "shMbrCode" => ""
    "electrBackUrl" => ""
    "electrMsg" => ""
  ]
]
*/

/**
 * 余额查询
 * 01:可用余额、02:实际余额、03:冻结余额、04:待结算余额、10:可用不可出金余额、11:不可用可出金余额、
 * 可提现金额=可用余额-可用不可出金余额+不可用可出金余额
 */
$data = [
    'tradeNo' => 'q2019091816061794677',
    'mbrCode' => '0030100000759343',
];
$result = $sdk->acntBlceQuery($data);
print_r($result);
/*
    array:2 [▼
      "info" => array:8 [▼
        "trxCode" => "400003"
        "version" => "01"
        "dataType" => "1"
        "reqSn" => "q2019092018170261186"
        "retCode" => "MCG00000"
        "errMsg" => "交易成功"
        "retTime" => "20190920181719"
        "salt" => "K95FrM142iakMHVbLFF+PCrQg+pDCEySqITDTTdWxPzAMyLbDCrqf2370rQD4O0ez2dNqCZ5vMxWbVBo7hgR+KUF5MTxMcxBBnfeqPWo9j/qcF+VmF47FQ6JLOCMe+1buQIWh+AFPIT+jbOng+Tv+87U4LjR56SQ ▶"
      ]
      "body" => array:5 [▼
        "mbrCode" => "0030100000759343"
        "rstCode" => "0"
        "rstMess" => "交易成功"
        "mbrNm" => "**"
        "LISTS" => array:6 [▼
          0 => array:2 [▼
            "amtType" => "01"
            "amt" => "1"
          ]
          1 => array:2 [▼
            "amtType" => "02"
            "amt" => "1"
          ]
          2 => array:2 [▼
            "amtType" => "03"
            "amt" => "0"
          ]
          3 => array:2 [▼
            "amtType" => "04"
            "amt" => "914"
          ]
          4 => array:2 [▼
            "amtType" => "10"
            "amt" => "1"
          ]
          5 => array:2 [▼
            "amtType" => "11"
            "amt" => "0"
          ]
        ]
      ]
    ]
 */

/**
 * 交易查询
 */

$data = [
    'tradeNo' => 'q2019091816061794677',
    'mercOrdNo' => 'd0110919091815', //订单单号
    'jrnno' => '180606120006175493', //匿名支付成功得到的交易单号
    'trxType' => '12001', //12001:B2C商城消费、12002:B2C商城消费合伙人模式、12005:用户缴费、12006:B2B商城消费、12007:B2B商城消费合伙人模式、12008:商品退款、19000:个人账户入金、19001:个人账户出金、19002:企业账户出金、19003:平台账户入金、19004:平台账户出金、21004:佣金分润、22007:平台缴费、22008:其他费用缴纳
];
$result = $sdk->tradeQuery($data);
print_r($result);
/*
[
  "info" => [
    "trxCode" => "400005"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "q2019091816061794677"
    "retCode" => "MCG00000"
    "errMsg" => "交易成功"
    "retTime" => "20190918160632"
    "salt" => "AoXufqlOxEXo/k80MUWAUS9yWDJJBztbf+D/Dtf0ukxhExEoOm2jUJw8hO14DUJMOPVH/y/98rjjWAbpHCW0IM5dKIg4DCZM5AFRU/rhGljofX0+X8eJa3O7kMrLVT3+wbQfdZXdBr5mhZZieHuDgkVzfMgJK1KI ▶"
  ]
  "body" => [
    "mercOrdNo" => "d0110919091815"
    "rstCode" => "0"
    "rstMess" => "交易成功"
    "jrnno" => "180606120006175493"
    "payMbrCode" => ""
    "purMbrCode" => ""
    "payPerName" => ""
    "purPerName" => ""
    "tradt" => "20190918"
    "tratm" => "151304"
    "otratm" => "2"
    "actTramt" => "2"
    "refundAmt" => "0"
    "feeAmt" => "1"
    "traSts" => "0"
    "ccy" => "CNY"
    "trxType" => "12001"
    "retNum" => "1"
    "LISTS" => array:1 [▶]
    "sumExpressAmt" => "0"
    "sumInsuranceAmt" => "0"
    "cntlist1" => "0"
    "remk" => ""
  ]
]
 */

/**
 * 平台电子登记簿余额信息查询
 * 手续费收入账户：下单退款的手续费的交互，
 * 手续费支出账户：支付渠道手续费的
 * 营销金账户：平台给商户的的一种优惠，主要就是涉及下单和退款的营销金收支。
 * 担保户：是为了买卖的安全设置的，只是个过渡账户。
 * 代收渠道手续费账户：存放给渠道的手续费
 */
$data = [
    'tradeNo' => 'q2019092415160304266',
    'mbrCode' => '',
];
$result = $sdk->platBlceQuery($data);
print_r($result);
/*
array:2 [▼
  "info" => array:8 [▼
    "trxCode" => "P2P024"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "q2019092415160304266"
    "retCode" => "MCG00000"
    "errMsg" => "交易成功"
    "retTime" => "20190924151627"
    "salt" => "NO5E5C3be6+rQxv7arUuhw9a/WoqGYWdkjGWzhVhbp3pTLyyyMLbBCfSx9fzsk3wlGYc9sM6X5CmusJhdwyd4JI250O0W1oZXjcrdWpWeUHWt1oCaPqwWyJ/WhCphyF5cJrVANIj6Q3j/LnK05CAlm0dOCapOf2J ▶"
  ]
  "body" => array:5 [▼
    "cashBal" => "0"
    "FeeInBal" => "2"
    "FeeOutBal" => "0"
    "MarketingBal" => "0"
    "GuaranteeBal" => "910"
  ]
]
 */

/**
 * 平台电子登记簿收支明细查询
 */
$data = [
    'feeAccTyp' => 0,//账户类型：0:手续费收入账户，1:手续费支出账户，2:营销账户，3:代收渠道手续费账户
];
$result = $sdk->platRecordsQuery($data);
print_r($result);

/*
array:2 [▼
  "info" => array:8 [▼
    "trxCode" => "400015"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "q2019092415313449321"
    "retCode" => "MCG00000"
    "errMsg" => "交易成功"
    "retTime" => "20190924153159"
    "salt" => "ddh1pIpqIeO/sS/NVJdotFoQhdVP6fVnAfz6/wbJaSDHEIslFYVH4eB1PYTEZWINUhImcU+ZIMWOjg4hQuGItpkHASDE2l3ZTzZJZnmp06NKph279AtqKqG8QUtl7fJjvw+lZqbntrEB0Cw9mNlZSHxkDWKDB/m7 ▶"
  ]
  "body" => array:6 [▼
    "rstCode" => "0"
    "rstMess" => "交易成功"
    "feeAccTyp" => "0"
    "totPage" => "1"
    "totCnt" => "2"
    "Lists" => array:2 [▼
      0 => array:13 [▼
        "mercOrdNo" => "d0110919091815"
        "tradeOrdNo" => "d0110919091815"
        "counterpartyNm" => "大技术部"
        "trxTypeName" => "消费"
        "tradt" => "20190923"
        "tratm" => "102609"
        "inoutFlag" => "1"
        "trAmt" => "1"
        "ccy" => "CNY"
        "remk" => "手续费收取-商户收入"
        "dsNm" => ""
        "prmk1" => ""
        "prmk2" => ""
      ]
      1 => array:13 [▼
        "mercOrdNo" => "d0110919091817"
        "tradeOrdNo" => "d0110919091817"
        "counterpartyNm" => "大技术部"
        "trxTypeName" => "消费"
        "tradt" => "20190918"
        "tratm" => "172857"
        "inoutFlag" => "1"
        "trAmt" => "1"
        "ccy" => "CNY"
        "remk" => "手续费收取-商户收入"
        "dsNm" => ""
        "prmk1" => ""
        "prmk2" => ""
      ]
    ]
  ]
]
 */


/**
 * 退款交易查询
 */
$data = [
    'tradeNo' => 'q2019110217284694886',
    'refundOrdNo' => 'd020891911011803',
];
$result = $sdk->refundQuery($data);
print_r($result);

/*
array:2 [
  "info" => array:8 [
    "trxCode" => "400038"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "q2019110410144132727"
    "retCode" => "MCG00000"
    "errMsg" => "交易成功"
    "retTime" => "20191104101614"
    "salt" => "dvo5HlEnr3msHxi2/4/JeUYR+GJoFNadvqHCzlfUrv4J1iycB+sr/zvwcEVObSu9x/0SVCrRb5lBwmMXVHF0gTz3rqmKxyc5DnVedVzZ8r9tU+Cq2YiWoxS/NvwfjudMZ4yFH7X94Ei3lA8Yu3zKaMSPOT/N13Hk/EfzBjTMZUY="
  ]
  "body" => array:25 [
    "rstCode" => "0"
    "rstMess" => "交易成功"
    "refundOrdNo" => "d020891911011803"
    "trxType" => "12008"
    "jrnno" => "180606130006590842"
    "agreest" => "Y"
    "traSts" => "0"
    "oriOrdAmt" => "2"
    "tradeNm" => "HC01 益生菌洁护牙膏,AM09 水感轻薄防晒霜SPF30PA++,BC04 水漾保湿蚕丝面膜,BC23 毛孔细致修护面膜...等"
    "tradeRmk" => "1001,1002,10003,10004...等"
    "tradeNum" => "10"
    "tradeAmt" => "2"
    "feeAmt" => "0"
    "expressAmt" => "0"
    "insuranceAmt" => "0"
    "platMrkAmt1" => "0"
    "servAmt" => "0"
    "platFeeAmt1" => "1"
    "remark" => "退款测试"
    "reFundMbrCode" => "100000759343"
    "benMbrCode" => ""
    "refundDt" => "20191101"
    "refundTm" => "180400"
    "expressOrdNo" => ""
    "rmk" => ""
  ]
]
 */




