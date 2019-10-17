<?php

namespace jzweb\sat\ccbll;

/**
 * 龙存管官方操作SDK
 *
 * Class client
 * @package jzweb\sat\ccbll
 */
class WxPayAdapter extends Adaptee implements Target
{

    const PAYTYPE_C = 'C';  //C:微信APP支付
    const PAYTYPE_D = 'D';  //D:支付宝APP支付
    const PAYTYPE_H = 'H';  //H:一码付（H5二维码）
    const PAYTYPE_I = 'I';  //I:微信扫码支付
    const PAYTYPE_J = 'J';  //J:微信公众号支付
    const PAYTYPE_K = 'K';  //K:建行信用卡分期支付
    const PAYTYPE_L = 'L';  //L:银联在线
    const PAYTYPE_M = 'M';  //M:建行网关对公
    const PAYTYPE_N = 'N';  //N:建行网关对私
    const PAYTYPE_P = 'P';  //P:支付宝扫码
    const PAYTYPE_Q = 'Q';  //Q:一码付（自定义二维码）
    const PAYTYPE_R = 'R';  //R:龙支付扫码
    const PAYTYPE_S = 'S';  //S:龙支付APP支付
    const PAYTYPE_W = 'W';  //W:微信小程序支付


    /**
     * 构造函数
     *
     * WxPayAdapter constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * 构造请求参数
     *
     * @param $payType
     * @param $data
     * @return array
     */
    private function buildRequestParams($payType, $data)
    {
        list($goods_str, $goods_ids_str, $quantity, $customer_mobile) = explode(";", $data['body']);
        return [
            'payType' => $payType,
            'mercOrdNo' => $data['out_trade_no'],
            'trxType' => '12001',//业务类型包括：12001:B2C商城消费、12002:B2C商城消费合伙人模式、12006:B2B商城消费、12007:B2B商城消费合伙人模式
            'trAmt' => $data['total_fee'],
            'tradt' => date('Ymd'), //交易日期
            'tratm' => date('His'), //交易时间
            'pageRetUrl' => $data['return_url'], //页面返回url
            'bgRetUrl' => $this->config['callback_pay_url'],   //后台通知url
            'ccy' => 'CNY',
            'platFeeAmt' => round($data['total_fee'] * 0.1),
            'prdSumAmt' => $data['total_fee'],
            'cnt' => 1,
            'Lists' => [
                [
                    'tradeOrdNo' => $data['out_trade_no'],
                    'mercMbrCode' => $data['trade_info'][0], //填写子商户信息
                    'tradeNm' => $goods_str,    //填产品商品串
                    'tradeRmk' => $goods_ids_str,   //填产品ID
                    'tradeNum' => $quantity,   //填写产品总数量
                    'tradeAmt' => $data['total_fee'],
                    'platFeeAmt1' => round($data['total_fee'] * 0.1),
                    'cMbl' => $customer_mobile,       //不填无法进行确认收货
                ],
            ],
            'ordValTmUnit' => 'H', //订单有效时间单位,D:日、H:时、M:分、S:秒
            'ordValTmCnt' => 6,
            'sumExpressAmt' => 0,
            'sumInsuranceAmt' => 0,
            'cntlist1' => 0,
            'Lists1' => [],
            'rmk2' => $data['appid'] ?: "", //预留字段2，微信小程序/微信公众号支付时必输，上送微信小程序/微信公众号的APPID
            'rmk3' => $data['openid'] ?: "", //预留字段3,微信小程序/微信公众号支付时必输，上送微信小程序/微信公众号的用户子标识OPENID
        ];
    }

    /**
     * 转换支付类型
     *
     * @param   [type] $payType                 [description]
     * @return  [type]                          [description]
     */
    private function changePayType($payType)
    {
        $payTypes = [
            'C' => 'trade.weixin.apppay',    //C:微信APP支付
            'D' => 'trade.alipay.apppay',    //D:支付宝APP支付
            'H' => 'trade.unionpay.native',  //H:一码付（H5二维码）
            'I' => 'trade.weixin.native',    //I:微信扫码支付
            'J' => 'trade.weixin.jspay',     //J:微信公众号支付
            'K' => '',  //K:建行信用卡分期支付
            'L' => '',  //L:银联在线
            'M' => '',  //M:建行网关对公
            'N' => '',  //N:建行网关对私
            'P' => 'trade.alipay.native',   //P:支付宝扫码
            'Q' => '',  //Q:一码付（自定义二维码）
            'R' => '',  //R:龙支付扫码
            'S' => '',  //S:龙支付APP支付
            'W' => 'trade.weixin.mppay',    //W:微信小程序支付
        ];

        return $payTypes[$payType];
    }

    /**
     *文件上传
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param url $file_url 软链接，外部可访问
     * @param string $oper_type 操作类型：01:新增，02:修改，默认为01
     * @return array
     */
    public function uploadFile($trade_no, $file_url, $oper_type = "01")
    {
        $data = [
            "tradeNo" => $trade_no,
            "fileUrl" => $file_url,
            "fileType" => "01", //文件格式:01:图片,02:PDF文件
            "operType" => $oper_type,
        ];

        $result = (new Adaptee($this->config))->fileUpload($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return ['return_code' => "SUCCESS", 'return_msg' => $result['body']['fileId']];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }

    }


    /**
     * 企业用户开电子登记簿
     * 必须有页面支撑，无法再测试用例里面直接调用
     * 会跳到建行页面输入结算账户名，结算卡号，交易密码，以及打款验证
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param int $entity_id 后台单位ID
     * @param string $entity_name 后台单位名称
     * @param string $legal 公司法人
     * @param string $legal_id_card 公司法人身份证
     * @param string $agent 联系人
     * @param string $agent_id_card 联系人身份证
     * @param int $agent_mobile 联系人的手机号码
     * @param string $buss_pic_id 公司营业执照
     * @param string $legal_front_pic_id 法人身份证正面
     * @param string $legal_back_pic_id 法人身份证反面
     * @param string $cert_pic_id 授权书图片ID
     * @param int $role_id 存管企业角色:100:企业店铺、101：企业买家、002:个体工商户店铺、300:交易市场物流企业、310:交易市场仓储企业
     * @param string $registrant 注册人身份，1:法定代表人、2:授权人
     * @param int $acc_type //账户类型，1:对私,2:对公
     * @param bool $is_mobile_view
     * @param string $return_url
     */
    public function createAccountByWeb($trade_no, $entity_id, $entity_name, $legal, $legal_id_card, $agent, $agent_id_card, $agent_mobile, $buss_pic_id, $legal_front_pic_id, $legal_back_pic_id, $cert_pic_id = "", $role_id = "100", $registrant = 1, $acc_type = 2, $is_mobile_view = false, $return_url = "")
    {

        $data = [
            'tradeNo' => $trade_no,
            'platCusNO' => $entity_id,
            'platRoleID' => $role_id,
            'busFullNm' => $entity_name,
            'registrant' => $registrant,
            'legalPerNm' => $legal,
            'legalPerIdNo' => $legal_id_card,
            'agent' => $agent,
            'agentIdNo' => $agent_id_card,
            'agentMbl' => $agent_mobile,
            'accType' => $acc_type,
            'pageRetUrl' => $return_url,
            'bgRetUrl' => $this->config['callback_create_account_url'],
            'bussLicenseID' => $buss_pic_id,
            'legalFrontPic' => $legal_front_pic_id,
            'legalBackPic' => $legal_back_pic_id,
            'certPic' => $cert_pic_id,
        ];
        (new Adaptee($this->config))->merchantAccount($data, $is_mobile_view);
    }

    /**
     * 企业用户信息变更
     * 会有两次异步通知，申请成功，审核成功，依据流水号reqSn识别，所以要记录当时请求的流水号reqSn
     * 或者单独一个接收通知接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $mch_code
     * @param $cert_pic_id
     * @param string $oper_type //12:银行账户开户行行号变更、13:银行账户开户行名称变更、14.法人变更、15.银行账号变更、23:被授权人变更
     * @param bool $is_mobile_view
     * @param string $return_url
     */
    public function merchantInfoChangeByWeb($trade_no, $mch_code, $cert_pic_id, $oper_type = "23", $is_mobile_view = false, $return_url = "")
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'pageRetUrl' => $return_url, //页面返回url
            'bgRetUrl' => $this->config['callback_update_account_url'],   //后台通知url
            'agent' => '刘建国',
            'agentIdType' => '01',
            'agentIdNo' => '430524198509243270',
            'agentMbl' => '13450418400',
            'certPic' => $cert_pic_id, //被授权书图片ID，上送文件获得，授权书模版找建行业务员要
        ];
        (new Adaptee($this->config))->merchantInfoChange($data, $is_mobile_view);
    }


    /**
     * 用户电子登记簿状态变更
     * 锁定、解锁：指的是这个用户什么操作都不能做
     * 冻结、解冻：指的是  关于资金类的交易  无法进行
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $mch_code
     * @param string $comments
     * @param string $effDate
     * @param string $expDate
     * @param string $oper_type 操作类型包括：04:锁定、05:解锁、06:冻结、07:解冻
     * @param bool $is_mobile_view 标记是否是移动端访问,默认否
     */
    public function accountStatusChange($trade_no, $mch_code, $comments, $effDate = "", $expDate = "", $oper_type = "04", $is_mobile_view = false)
    {

        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'effectiveFlag' => 'Y', //是否立刻生效，Y:是、N:否
            'effDt' => $effDate ? date('Ymd', strtotime($effDate)) : date('Ymd'), //生效日期，当为立刻生效时，此栏位允许为空
            'effTm' => $effDate ? date('His', strtotime($effDate)) : date('His'), //生效时间，当为立刻生效时，此栏位允许为空
            'expDt' => $effDate ? date('Ymd', strtotime($expDate)) : '20990101', //失效日期 允许为空
            'expTm' => $effDate ? date('His', strtotime($expDate)) : '000000', //失效时间 允许为空
            'rmk1' => $comments, //变更原因
        ];
        $result = (new Adaptee($this->config))->accountStatusChange($data, $is_mobile_view);
        if ($result && $result['body']['rstCode'] == "0") {
            return $result['body'];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }


    /**
     * 交易密码重置
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $mch_code
     * @param string $oper_type 17:交易密码修改、18:交易密码重置
     * @param bool $is_mobile_view 标记是否是移动端访问,默认否
     * @param string $return_url
     */
    public function restPwdByWeb($trade_no, $mch_code, $oper_type = "17", $is_mobile_view = false, $return_url = "")
    {

        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'pageRetUrl' => $return_url, //页面返回url
            'bgRetUrl' => $this->config['callback_rest_pwd_url'],   //后台通知url
        ];
        (new Adaptee($this->config))->passwordSetting($data, $is_mobile_view);
    }

    /**
     * 微信公众号支付
     *
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $appid
     * @param string $openid
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed|void
     */
    public function weixinJsPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.weixin.native
     * 微信扫码支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixiNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_J, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.apppay
     * 微信APP支付，调用统一下单接口【拉起微信APP支付,微信官方原生的】
     * todo 我们目前的产品,暂时没有开通该服务
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.weixin.apppay2
     * 微信APP+支付，调用统一下单接口【拉起优洛微信小程序支付】
     * 我们目前的产品，开通了该服务
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay2($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_C, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.h5pay
     * 微信h5支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinH5Pay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.weixin.mppay
     * 适用微信小程序中拉起微信支付。
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $appid
     * @param string $openid
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinMpPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_W, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.micropay
     * 微信刷卡支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function weixinMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.native
     * 支付宝扫码支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_P, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.alipay.jspay
     * 支付宝公众号支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function alipayJsPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.h5pay
     * 支付宝H5支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayH5Pay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.micropay
     * 支付宝小额支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function alipayMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.unionpay.native
     * 银联扫码支付，调用统一下单接。
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function unionpayNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.unionpay.micropay
     * 银联刷卡支付，调用统一下单接。
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function unionpayMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }


    /**
     * 回调通知验签
     * 商户系统对于支付结果通知的内容一定要做签名验证
     * 防止数据泄漏导致出现“假通知”，造成资 金损失
     *
     * @param string $xml
     * @return array|bool
     */
    public function verifySignCallBack($xml)
    {
        return (new Adaptee($this->config))->asynchroNotice($xml);
    }

    /**
     * 订单查询接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderQuery($trade_no, $out_trade_no)
    {
        $data = [
            'tradeNo' => $trade_no,
            'mercOrdNo' => $out_trade_no, //订单单号
            'trxType' => '12001', //12001:B2C商城消费、12002:B2C商城消费合伙人模式、12005:用户缴费、12006:B2B商城消费、12007:B2B商城消费合伙人模式、12008:商品退款、19000:个人账户入金、19001:个人账户出金、19002:企业账户出金、19003:平台账户入金、19004:平台账户出金、21004:佣金分润、22007:平台缴费、22008:其他费用缴纳
        ];
        $result = (new Adaptee($this->config))->tradeQuery($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'bank_no' => "",
                'bank_type' => "",
                'cash_fee' => $result['body']['actTramt'],
                'fee_type' => $result['body']['ccy'],
                'out_trade_no' => $out_trade_no,
                'result_code' => "SUCCESS",
                'return_code' => "SUCCESS",
                'sign' => $result['info']['salt'],
                'sub_openid' => "",
                'third_trans_id' => "",
                'time_end' => $result['body']['tradt'] . $result['body']['tratm'],
                'total_fee' => $result['body']['otratm'],
                'trade_state' => "SUCCESS",
                'trade_type' => '',
                'transaction_id' => $result['body']['jrnno'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 订单退款接口
     * 注意：如果不填手续费，那么手续费将由商户承担
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param string $out_refund_no
     * @param int $total_fee
     * @param int $refund_fee
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefund($trade_no, $out_trade_no, $out_refund_no, $total_fee, $refund_fee, $body)
    {
        list($goods_str, $goods_ids_str, $remark) = explode(";", $body);
        $data = [
            'tradeNo' => $trade_no,
            'refundOrdNo' => $out_refund_no,
            'trxType' => '12008', //12008:商品退款，12014:佣金退款
            'operType' => $total_fee == $refund_fee ? '21' : '22', //针对子订单，子订单退全额就是全额退款
            'oriOrdNo' => $out_trade_no,
            'oriOrdAmt' => $refund_fee,
            'refundDt' => date('Ymd'),
            'refundTm' => date('His'),
            'tradeOrdNo' => $out_trade_no,
            'tradeNm' => $goods_str,
            'tradeRmk' => $goods_ids_str,
            'tradeNum' => 1,
            'tradeAmt' => $refund_fee,
            'feeAmt' => 0, //从商户余额账户扣除一笔手续费到平台账户，应保持为0
            'platFeeAmt1' => round($refund_fee * 0.1), //填了手续费由平台承担，不填由商户承担
            'remark' => $remark,
        ];
        $result = (new Adaptee($this->config))->refund($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'mch_id' => '',
                'out_refund_no' => $out_refund_no,
                'out_trade_no' => $out_trade_no,
                'refund_channel' => 'ORIGINAL',
                'refund_fee' => $refund_fee,
                'refund_id' => $result['body']['jrnno'],
                'result_code' => 'SUCCESS',
                'return_code' => 'SUCCESS',
                'sign' => $result['info']['salt'],
                'third_trans_id' => '',
                'total_fee' => $total_fee,
                'transaction_id' => $result['body']['jrnno'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 订单退款进度查询接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefundQuery($trade_no, $out_trade_no, $out_refund_no)
    {
        $data = [
            'tradeNo' => $trade_no,
            'mercOrdNo' => $out_trade_no, //交易订单单号
            'mercRfOrdNo' => $out_refund_no, //退款订单单号
            'trxType' => '12008', //12001:B2C商城消费、12002:B2C商城消费合伙人模式、12005:用户缴费、12006:B2B商城消费、12007:B2B商城消费合伙人模式、12008:商品退款、19000:个人账户入金、19001:个人账户出金、19002:企业账户出金、19003:平台账户入金、19004:平台账户出金、21004:佣金分润、22007:平台缴费、22008:其他费用缴纳
        ];
        $result = (new Adaptee($this->config))->tradeQuery($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'cash_fee' => $result['body']['actTramt'],
                'fee_type' => $result['body']['ccy'],
                'mch_id' => "",
                'out_refund_no_0' => $out_refund_no,
                'out_trade_no' => $out_trade_no,
                'refund_channel_0' => "ORIGINAL",
                'refund_count' => $result['body']['retNum'],
                'refund_fee_0' => $result['body']['refundAmt'],
                'refund_id_0' => $result['body']['jrnno'],
                'refund_status_0' => "SUCCESS",
                'refund_success_time_0' => $result['body']['tradt'] . $result['body']['tratm'],
                'result_code' => "SUCCESS",
                'return_code' => "SUCCESS",
                'sign' => $result['info']['salt'],
                'third_trans_id' => "",
                'total_fee' => $result['body']['otratm'],
                'transaction_id' => $result['body']['jrnno'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 发货通知
     * @param   string $trade_no                [交易流水号]
     * @param   string $mch_code                [龙存管商户号]
     * @param   string $out_trade_no            [交易订单号]
     * @param   string $trxType                 [业务类型,12003:商城收货确认]
     * @param   string $remark                  [备注]
     * @return  array|mixed
     */
    public function goodsNotice($trade_no, $mch_code, $out_trade_no, $trxType = '12003', $remark = '')
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'trxType' => $trxType,
            'oriOrdNo' => $out_trade_no,
            'tradeOrdNo' => $out_trade_no,
            'remark' => $remark,
        ];
        $result = (new Adaptee($this->config))->goodsNotice($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'result_code' => 'SUCCESS',
                'return_code' => 'SUCCESS',
                'jrnno' => $result['body']['jrnno'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 超时后平台代为确认收货
     * @param   string $trade_no                [交易流水号]
     * @param   string $mch_code                [龙存管商户号]
     * @param   string $jrnno                   [龙存管订单流水号]
     * @param   string $out_trade_no            [交易订单号]
     * @param   string $total_fee               [交易金额]
     * @param   string $trxType                 [业务类型,12010:平台确认收货]
     * @return  array|mixed
     */
    public function insteadToConfirm($trade_no, $mch_code, $jrnno, $out_trade_no, $total_fee, $trxType = '12010')
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'trxType' => $trxType,
            'jrnno' => $jrnno,
            'ordNo' => $out_trade_no,
            'traAmt' => $total_fee,
            'tradt' => date('Ymd'),
            'tratm' => date('His'),
        ];
        $result = (new Adaptee($this->config))->goodsNotice($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'result_code' => 'SUCCESS',
                'return_code' => 'SUCCESS',
                'jrnno' => $result['body']['jrnno'],
                'success_time' => $result['body']['tradt'] . $result['body']['tratm'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 用户确认
     * @param   string $trade_no                [交易流水号]
     * @param   string $mch_code                [龙存管商户号]
     * @param   string $jrnno                   [龙存管订单流水号]
     * @param   string $trxType                 [业务类型,12003:商城收货确认，12008:商品退款，12014:佣金退款；]
     * @param   string $agreest                 [交易确认状态,Y:同意、N:不同意/延迟确认]
     * @param   string $return_url              [页面返回url]
     * @return  array|mixed
     */
    public function userToConfirm($trade_no, $mch_code, $jrnno, $trxType = '12008', $agreest = 'Y', $return_url = '')
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'trxType' => $trxType,
            'jrnno' => $jrnno, //如果是收货确认，用发货通知的交易单号，如果是退款，用退款申请的交易单号
            'agreest' => 'Y',
            'pageRetUrl' => $return_url,
            'bgRetUrl' => $this->config['callback_user_confirm_url'],
        ];
        $result = (new Adaptee($this->config))->goodsNotice($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'result_code' => 'SUCCESS',
                'return_code' => 'SUCCESS',
                'jrnno' => $result['body']['jrnno'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }
}