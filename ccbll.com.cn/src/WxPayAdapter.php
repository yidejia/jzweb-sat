<?php

namespace jzweb\sat\ccbll;

use jzweb\sat\ccbll\Handler\Check;
use jzweb\sat\ccbll\Handler\KeyManage;
use jzweb\sat\ccbll\Handler\Merchant;
use jzweb\sat\ccbll\Handler\Notice;
use jzweb\sat\ccbll\Handler\Query;
use jzweb\sat\ccbll\Handler\Trade;
use jzweb\sat\ccbll\Handler\Transfer;

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
            'trAmt' => $data['total_fee'] * 100,
            'tradt' => date('Ymd'), //交易日期
            'tratm' => date('His'), //交易时间
            'pageRetUrl' => $data['return_url'], //页面返回url
            'bgRetUrl' => $this->config['callback_url'],   //后台通知url
            'ccy' => 'CNY',
            'platFeeAmt' => $data['total_fee'] * 10 * 0.10,
            'prdSumAmt' => $data['total_fee'] * 100,
            'cnt' => 1,
            'Lists' => [
                [
                    'tradeOrdNo' => $data['out_trade_no'],
                    'mercMbrCode' => $data['trade_info'][0], //填写子商户信息
                    'tradeNm' => $goods_str,    //填产品商品串
                    'tradeRmk' => $goods_ids_str,   //填产品ID
                    'tradeNum' => $quantity,   //填写产品总数量
                    'tradeAmt' => $data['total_fee'] * 100,
                    'platFeeAmt1' => $data['total_fee'] * 10 * 0.10,
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
     * 微信公众号支付
     *
     * @param string $appid
     * @param string $openid
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed|void
     */
    public function weixinJsPay($appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_I, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.native
     * 微信扫码支付，调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixiNative($out_trade_no, $total_fee, $body, $ip = "127.0.0.1")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_J, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.apppay
     * 微信APP支付，调用统一下单接口【拉起微信APP支付,微信官方原生的】
     * todo 我们目前的产品,暂时没有开通该服务
     *
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay($out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_C, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.apppay2
     * 微信APP+支付，调用统一下单接口【拉起优洛微信小程序支付】
     * 我们目前的产品，开通了该服务
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay2($out_trade_no, $total_fee, $body, $ip = "127.0.0.1")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_C, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.h5pay
     * 微信h5支付，调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinH5Pay($out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.weixin.mppay
     * 适用微信小程序中拉起微信支付。
     *
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
    public function weixinMpPay($appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_W, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.micropay
     * 微信刷卡支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function weixinMicroPay($out_trade_no, $total_fee, $body, $ip = "127.0.0.1")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.native
     * 支付宝扫码支付，调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayNative($out_trade_no, $total_fee, $body, $ip = "127.0.0.1")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_P, func_get_args());
        return (new Adaptee($this->config))->anonyPay($data);
    }

    /**
     * trade.alipay.jspay
     * 支付宝公众号支付，调用统一下单接口
     *
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function alipayJsPay($out_trade_no, $total_fee, $body, $ip = "127.0.0.1")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.h5pay
     * 支付宝H5支付，调用统一下单接口
     *
     * @param string $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayH5Pay($out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.micropay
     * 支付宝小额支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function alipayMicroPay($out_trade_no, $total_fee, $body, $ip = "127.0.0.1")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.unionpay.native
     * 银联扫码支付，调用统一下单接。
     *
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function unionpayNative($out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.unionpay.micropay
     * 银联刷卡支付，调用统一下单接。
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function unionpayMicroPay($out_trade_no, $total_fee, $body, $ip = "127.0.0.1")
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
     * @param string $out_trade_no
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderQuery($out_trade_no)
    {
        $data = [
            'mercOrdNo' => $out_trade_no, //订单单号
            'trxType' => '12001', //12001:B2C商城消费、12002:B2C商城消费合伙人模式、12005:用户缴费、12006:B2B商城消费、12007:B2B商城消费合伙人模式、12008:商品退款、19000:个人账户入金、19001:个人账户出金、19002:企业账户出金、19003:平台账户入金、19004:平台账户出金、21004:佣金分润、22007:平台缴费、22008:其他费用缴纳
        ];
        $result = (new Adaptee($this->config))->tradeQuery($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'bank_no' => "65101018120191014150496892",
                'bank_type' => "WEIXIN_XCX",
                'cash_fee' => "16600",
                'fee_type' => "CNY",
                'out_trade_no' => $result['body']['mercOrdNo'],
                'result_code' => "SUCCESS",
                'return_code' => "SUCCESS",
                'sign' => "C2E765B8961BE763A6E770F3FAC87EBD",
                'sub_openid' => "oVqkD0ZAQOLN3TcPaoAlAPLG7F4w",
                'third_trans_id' => "100219101476335989",
                'time_end' => "20191014150647",
                'total_fee' => "16600",
                'trade_state' => "SUCCESS",
                'trade_type' => "trade.weixin.mppay",
                'transaction_id' => "2638474520191014150318952"
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 订单退款接口
     *
     * @param string $out_trade_no
     * @param string $out_refund_no
     * @param int $total_fee
     * @param int $refund_fee
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefund($out_trade_no, $out_refund_no, $total_fee, $refund_fee)
    {
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
        $result = (new Adaptee($this->config))->refund($data);
        if ($result && $result['body']['rstCode'] == "0") {

        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 订单退款进度查询接口
     *
     * @param string $out_trade_no
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefundQuery($out_trade_no)
    {
        $data = [
            'mercOrdNo' => $out_trade_no, //订单单号
            'trxType' => '12001', //12001:B2C商城消费、12002:B2C商城消费合伙人模式、12005:用户缴费、12006:B2B商城消费、12007:B2B商城消费合伙人模式、12008:商品退款、19000:个人账户入金、19001:个人账户出金、19002:企业账户出金、19003:平台账户入金、19004:平台账户出金、21004:佣金分润、22007:平台缴费、22008:其他费用缴纳
        ];
        $result = (new Adaptee($this->config))->tradeQuery($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'cash_fee' => "517490",
                'fee_type' => "CNY",
                'mch_id' => "26384745",
                'out_refund_no_0' => "d0235A19070040",
                'out_trade_no' => "d0235A19070040",
                'refund_channel_0' => "ORIGINAL",
                'refund_count' => "1",
                'refund_fee_0' => "517490",
                'refund_id_0' => "2638474520190803190051771",
                'refund_status_0' => "SUCCESS",
                'refund_success_time_0' => "20190803191601",
                'result_code' => "SUCCESS",//不能动
                'return_code' => "SUCCESS",//不能动
                'sign' => "5C2857ECC8E31C1C31702841F15E3FA2",
                'third_trans_id' => "100219073015835882",
                'total_fee' => "517490",
                'transaction_id' => "2638474520190730110026983"
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }


}