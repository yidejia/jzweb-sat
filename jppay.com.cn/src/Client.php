<?php

namespace jzweb\sat\jppay;

use jzweb\sat\jppay\Handler\JoinPay;
use jzweb\sat\jzpay\JzPayInterface;

/**
 * 封装聚合支付操作SDK
 * 我们暂时开通的产品是app+支付
 *
 * Class client
 * @package jzweb\sat\crbank
 */
class Client implements JzPayInterface
{
    private $joinPay;
    private $trade_no = "";

    /**
     * 构造函数
     *
     * client constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->joinPay = new JoinPay($config);
    }


    /**
     * trade.weixin.jspay
     * 微信公众号支付，适用原生公众号支付。
     *
     * @param string $appid
     * @param string $openid
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinJsPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['sub_appid'] = $appid;
        $params['sub_openid'] = $openid;
        $params['req_type'] = "wxjsapi";
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.weixin.jspay";
        $params['op_term_tp'] = "WEB";
        $return_url && $params['return_url'] = $return_url;
        return $this->joinPay->unifiedorder($params);
    }

    /**
     * trade.weixin.native
     * 微信扫码支付，调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixiNative($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.weixin.native";
        $params['op_term_tp'] = "WEB";
        return $this->joinPay->unifiedorder($params);
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
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.weixin.apppay";
        $params['op_term_tp'] = "WEB";
        $return_url && $params['return_url'] = $return_url;
        return $this->joinPay->unifiedorder($params);
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
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay2($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.weixin.apppay2";
        $params['op_term_tp'] = "WEB";
        return $this->joinPay->unifiedorder($params);
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
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinH5Pay($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.weixin.h5pay";
        $params['op_term_tp'] = "WEB";
        $return_url && $params['return_url'] = $return_url;
        return $this->joinPay->unifiedorder($params);
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
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinMpPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['sub_appid'] = $appid;
        $params['sub_openid'] = $openid;
        $params['req_type'] = "wxjsapi";
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.weixin.mppay";
        $params['op_term_tp'] = "WEB";
        $return_url && $params['return_url'] = $return_url;
        return $this->joinPay->unifiedorder($params);
    }

    /**
     * trade.weixin.micropay
     * 微信刷卡支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     *
     * @return array
     */
    public function weixinMicroPay($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
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
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayNative($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.alipay.native";
        $params['op_term_tp'] = "WEB";
        return $this->joinPay->unifiedorder($params);
    }


    /**
     * trade.alipay.jspay
     * 支付宝公众号支付，调用统一下单接口
     *
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     *
     * @return array
     */
    public function alipayJsPay($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
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
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayH5Pay($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.alipay.h5pay";
        $params['op_term_tp'] = "WEB";
        $return_url && $params['return_url'] = $return_url;
        return $this->joinPay->unifiedorder($params);
    }

    /**
     * trade.alipay.micropay
     * 支付宝小额支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     *
     * @return array
     */
    public function alipayMicroPay($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
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
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function unionpayNative($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        if (time() > strtotime('2020-03-31 23:55:00')) {
            return ['error_code' => 888889, 'err_code_dsc' => '该支付渠道已停止支持'];
        }

        $params = [];
        $params['body'] = $body;
        $params['out_trade_no'] = $out_trade_no;
        $params['total_fee'] = $total_fee;
        $params['fee_type'] = "CNY";
        $params['spbill_create_ip'] = $ip;
        $params['trade_type'] = "trade.unionpay.native";
        $params['op_term_tp'] = "WEB";
        $return_url && $params['return_url'] = $return_url;
        return $this->joinPay->unifiedorder($params);
    }

    /**
     * trade.unionpay.micropay
     * 银联刷卡支付，调用统一下单接。
     *
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     *
     * @return array
     */
    public function unionpayMicroPay($trade_no, $out_trade_no, $total_fee, $body = "伊的家商城订单", $ip = "127.0.0.1", $return_url = "")
    {
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }


    /**
     * 回调通知验签
     * 商户系统对于支付结果通知的内容一定要做签名验证
     * 防止数据泄漏导致出现“假通知”，造成资 金损失
     *
     * @param string $xml
     *
     * @return array|bool
     */
    public function verifySignCallBack($xml)
    {
        return $this->joinPay->verifySignCallBack($xml);
    }

    /**
     * 订单查询接口
     *
     * @param string $trade_no
     * @param string $out_trade_no
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderQuery($trade_no, $out_trade_no)
    {
        return $this->joinPay->orderQuery($out_trade_no);
    }


    /**
     * 订单退款接口
     *
     * @param string $trade_no
     * @param string $out_trade_no
     * @param string $out_refund_no
     * @param int $total_fee
     * @param int $refund_fee
     * @param string $body
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefund($trade_no, $out_trade_no, $out_refund_no, $total_fee, $refund_fee, $mrk_fee = 0, $body = "伊的家商城订单", $trxType = '12008')
    {
        return $this->joinPay->orderRefund($out_trade_no, $out_refund_no, $total_fee, $refund_fee);
    }


    /**
     * 订单退款进度查询接口
     *
     * @param string $trade_no
     * @param string $out_trade_no
     * @param string $out_refund_no
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefundQuery($trade_no, $out_trade_no, $out_refund_no = "")
    {
        return $this->joinPay->orderRefundQuery($out_trade_no);
    }

}