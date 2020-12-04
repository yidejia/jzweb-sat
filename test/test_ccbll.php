<?php

/**
 * 龙存管测试用例
 * 所有h5接口必须有页面支撑，移动端访问第二个参数为true
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
    //日志路径
    'log_path' => './' . date('Ym') . "/",
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
use \jzweb\sat\ccbll\Lib\Log;
$sdk = new \jzweb\sat\ccbll\Client($config);

(new Log($config))->log('test');
echo 1;
exit;

/** 文件上传 */
$data = [
  "tradeNo" => "m2019090318350376998", //交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
  "fileUrl" => "http://106.75.132.218:80/storage/20190902/1.pdf", //软链接，外部可访问
  "fileType" => "02", //文件格式:01:图片,02:PDF文件
  "operType" => "01", //操作类型：01:新增，02:修改，默认为01
];

$result = $sdk->fileUpload($data);
print_r($result);
/*
[
  "info" => [
    "trxCode" => "200014"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "m2019090715295380714"
    "retCode" => "MCG00000"
    "errMsg" => "交易成功"
    "retTime" => "20190907153032"
    "salt" => "ptbiT95UxxmNn+liGc/ho8/y/HEVkl7rSmKGuvuX/fd7HFL7sMOMwr7SoiV5tAc8aVJus8mfXfuEpBV+aCc+yMhshy3T0WVHJV4XSRInGYBHokG1xKSf/mYtXKRvYT6+s4iP9pKqbje393kUn76mU4j5NGFYwo36WuDB9U3seYE="
  ]
  "body" => [
    "rstCode" => "0"
    "rstMess" => "交易成功"
    "fileId" => "180606130006104451"
    "tradt" => "20190907"
    "tratm" => "153032"
    "operType" => "01"
  ]
]
*/

/**
 * 企业用户开电子登记簿
 * 必须有页面支撑，无法再测试用例里面直接调用
 * 会跳到建行页面输入结算账户名，结算卡号，交易密码，以及打款验证
 */
$data = [
    'tradeNo' => 'm2019090318350376998',
    'platCusNO' => '1109', //我们平台的商户号
    'platRoleID' => '100', //存管企业角色:100:企业店铺、101：企业买家、002:个体工商户店铺、300:交易市场物流企业、310:交易市场仓储企业
    'busFullNm' => '大技术部',
    'registrant' => '1', //注册人身份，1:法定代表人、2:授权人
    'legalPerNm' => '刘建国',
    'legalPerIdNo' => '430524198509243270',
    'agent' => '刘建国',
    'agentIdNo' => '430524198509243270',
    'agentMbl' => '13422318178',
    'accType' => '2', //账户类型，1:对私,2:对公
    'pageRetUrl' => 'http://106.75.132.218:80/api/test/index', //页面返回url
    'bgRetUrl' => 'http://106.75.132.218:80/api/test/notice',   //后台通知url
    'bussLicenseID' => '180606130006104451', //营业执照图片ID，文件上传获得fileId
    'legalFrontPic' => '180606120006104472', //法人身份证正面图片ID，文件上传获得fileId
    'legalBackPic' => '180606110006104473', //法人身份证反面图片ID，文件上传获得fileId
];
$result = $sdk->merchantAccount($data);
// $result = $sdk->merchantAccount($data, true);  //移动端访问第二个参数为true
print_r($result);
/*
[
    'info' =>
    array (
        'trxCode' => '110004',
        'version' => '01',
        'dataType' => 'json',
        'reqSn' => 'm2019090918171942218',
        'retCode' => 'MCA00000',
        'errMsg' => 'SUCCESS',
        'retTime' => '20190909182241',
    ),
    'body' =>
    array (
        'rstCode' => '1',
        'rstMess' => 'SUCCESS',
        'jrnno' => '180606180006125994',
        'userSts' => '3',
        'accountNm' => '**',
        'account' => '9876************3210',
        'bankCode' => '0104',
        'bankNum' => '',
        'bankNm' => '中国银行',
        'mbrCode' => '0030100000759343',
    ),
]
*/

/**
 * 企业用户信息变更
 * 会有两次异步通知，申请成功，审核成功，依据流水号reqSn识别，所以要记录当时请求的流水号reqSn
 * 或者单独一个接收通知接口
 */
$data = [
    'tradeNo' => 'm2019092016014761149',
    'mbrCode' => '0030100000759343',
    'operType' => '23', //12:银行账户开户行行号变更、13:银行账户开户行名称变更、14.法人变更、15.银行账号变更、23:被授权人变更
    'pageRetUrl' => 'http://106.75.132.218:80/api/test/index', //页面返回url
    'bgRetUrl' => 'http://106.75.132.218:80/api/test/notice',   //后台通知url
    'agent' => '刘建国',
    'agentIdType' => '01',
    'agentIdNo' => '430524198509243270',
    'agentMbl' => '13450418400',
    'certPic' => '180606120006104472', //被授权书图片ID，上送文件获得，授权书模版找建行业务员要
];
$result = $sdk->merchantInfoChange($data);
// $result = $sdk->merchantInfoChange($data, true);  //移动端访问第二个参数为true
print_r($result);
/*
    异步通知结果
    'info' =>
    array (
        'trxCode' => '110007',
        'version' => '01',
        'dataType' => '1',
        'reqSn' => 'm2019092016014761149',
        'retCode' => '00000',
        'errMsg' => '',
        'retTime' => '20190920160218',
    ),
    'body' =>
    array (
        'rstCode' => '1',
        'rstMess' => 'SUCCESS',
        'jrnno' => '180606110006187637',
        'mbrCode' => '0030100000759343',
        'operType' => '23',
        'accountNm' => '*',
        'account' => '****',
        'bankCodeName' => '****',
        'bankNum' => '****',
        'bankNm' => '****',
        'legalPerIdTyp' => '',
        'legalPerIdNo' => '****',
        'tradt' => '20190920',
        'tratm' => '160214',
        'agent' => '刘建国',
        'agentIdType' => '01',
        'agentIdNo' => '4305****3270',
        'agentMbl' => '13450418400',
    ),
    )
 */

/**
 * 用户电子登记簿状态变更
 * 锁定、解锁：指的是这个用户什么操作都不能做
 * 冻结、解冻：指的是  关于资金类的交易  无法进行
 */
$data = [
    'tradeNo' => 'm2019092016014761149',
    'mbrCode' => '0030100000759343',
    'operType' => '23', //操作类型包括：04:锁定、05:解锁、06:冻结、07:解冻
    'effectiveFlag' => 'Y', //是否立刻生效，Y:是、N:否
    'effDt' => date('Ymd'), //生效日期，当为立刻生效时，此栏位允许为空
    'effTm' => date('His'), //生效时间，当为立刻生效时，此栏位允许为空
    'expDt' => '20990101', //失效日期 允许为空
    'expTm' => '000000', //失效时间 允许为空
    'rmk1' => '测试', //变更原因
];
$result = $sdk->accountStatusChange($data);
print_r($result);



/**
 * 交易密码安全设置
 */
$data = [
    'tradeNo' => 'm2019092016014761149',
    'mbrCode' => '0030100000759343',
    'operType' => '17', //17:交易密码修改、18:交易密码重置
    'pageRetUrl' => 'http://106.75.132.218:80/api/test/index', //页面返回url
    'bgRetUrl' => 'http://106.75.132.218:80/api/test/notice',   //后台通知url
];
$result = $sdk->passwordSetting($data);
// $result = $sdk->passwordSetting($data, true); //移动端访问第二个参数为true
print_r($result);


/**
 * 批量开户
 */
$data = [
    'tradeNo' => 'm2019103017593473606',
    'platCusNO' => '1112',
    'platRoleID' => '100',
    'creditCd' => 'adsafkasfhsakjfhsaq',
    'busFullNm' => '大技术部3',
    'registrant' => 1,
    'legalPerNm' => '刘建3',
    'legalPerIdTyp' => '01',
    'legalPerIdNo' => '232134799232134715',
    'mercFlg' => 1,
    'receAcTyp' => 2,
    'receAc' => '232134799232134711',
    'receAcName' => '伊的家商行2',
    'receAcBankName' => '建设银行',
    'receAcBankNm' => '0105',
    'recePerId' => '232134799232134715',
    'receMbl' => '13422318178',
    'receTyp' => 1,
    'isAcFlg' => 1,
    'busRating' => '00',
    'bgRetUrl' => 'http://106.75.132.218/ccbpay/notify_create_account.php',
    'bussLicenseID' => '180606130006104451',
    'legalFrontPic' => '180606120006104472',
    'legalBackPic' => '180606110006104473',
    'openAccType' => 1,
];
$result = $sdk->merchantCreateBatch($data);
print_r($result);
/*
array:2 [
  "info" => array:8 [
    "trxCode" => "120004"
    "version" => "01"
    "dataType" => "1"
    "reqSn" => "m2019103017593473606"
    "retCode" => "URM00000"
    "errMsg" => "交易成功"
    "retTime" => "20191030180059"
    "salt" => "Xn1TksvrhTjxSAMjXZu2NpDkfKw4eMctlcY7XSumEtdIxMe9jQSmRdBMA43+c3wq1CpNzkTI74PpfOhyi3xNnjqZAggAMJMd+LHIgqyL6jQx3zZ3idLmjvVarw3z9e5wD/eNteRyHotDapeZXJLS6XdDO52A8cZSACWNhO5mWU8="
  ]
  "body" => array:6 [
    "rstCode" => "0"
    "rstMess" => "交易成功"
    "jrnno" => "180606170006559990"
    "userSts" => "8"
    "mbrCode" => "0030100000863367"
    "payAgrNo" => ""
  ]
]
 */

/**
 * 异步通知
 Array
(
    [info] => Array
        (
            [trxCode] =>
            [version] =>
            [dataType] => json
            [reqSn] =>
            [retCode] => MCA00000
            [errMsg] => SUCCESS
            [retTime] => 20191030180059
        )

    [body] => Array
        (
            [rstCode] => 0
            [rstMess] => SUCCESS
            [mbrCode] => 0030100000863367
            [platCusNO] => 1112
            [platCusName] => 刘**
            [userSts] => 8
            [remark] =>
        )
）
 */