<?php

namespace jzweb\sat;


/**
 * 经传集团聚合支付接口
 *
 * Class client
 * @package jzweb\sat\ccbll
 */
interface JzPayInterface
{
    /**
     * trade.weixin.jspay
     * 微信公众号支付，适用原生公众号支付。
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
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
    public function weixinJsPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.weixin.native
     * 微信扫码支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixiNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.weixin.apppay
     * 微信APP支付，调用统一下单接口【拉起微信APP支付,微信官方原生的】
     * todo 我们目前的产品,暂时没有开通该服务
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.weixin.apppay2
     * 微信APP+支付，调用统一下单接口【拉起优洛微信小程序支付】
     * 我们目前的产品，开通了该服务
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay2($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.weixin.h5pay
     * 微信h5支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinH5Pay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.weixin.mppay
     * 适用微信小程序中拉起微信支付。
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
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
    public function weixinMpPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.weixin.micropay
     * 微信刷卡支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function weixinMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.alipay.native
     * 支付宝扫码支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");


    /**
     * trade.alipay.jspay
     * 支付宝公众号支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function alipayJsPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.alipay.h5pay
     * 支付宝H5支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayH5Pay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.alipay.micropay
     * 支付宝小额支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     *
     * @return array
     */
    public function alipayMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.unionpay.native
     * 银联扫码支付，调用统一下单接。
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function unionpayNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");

    /**
     * trade.unionpay.micropay
     * 银联刷卡支付，调用统一下单接。
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function unionpayMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "");


    /**
     * 回调通知验签
     * 商户系统对于支付结果通知的内容一定要做签名验证
     * 防止数据泄漏导致出现“假通知”，造成资 金损失
     *
     * @param string $xml
     * @return array|bool
     */
    public function verifySignCallBack($xml);

    /**
     * 订单查询接口
     *
     * @param string $trade_no 交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderQuery($trade_no, $out_trade_no);


    /**
     * 订单退款接口
     *
     * @param string $trade_no 交易流水号全局唯一,交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no
     * @param string $out_refund_no
     * @param int $total_fee
     * @param int $refund_fee
     * @param string $body 退款备注,目前只有建行退关通道需要,其他平台暂时不需要该字段
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefund($trade_no, $out_trade_no, $out_refund_no, $total_fee, $refund_fee, $body = "伊的家商城订单");


    /**
     * 订单退款进度查询接口
     *
     * @param string $trade_no 交易流水号全局唯一,目前指有建行的通道需要该参数,其他行内通道不需要该参数
     * @param string $out_trade_no 交易单号
     * @param string $out_refund_no 退款单号 目前指有优洛的通道需要该参数,建行的通道不需要该参数
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefundQuery($trade_no, $out_trade_no, $out_refund_no);
}
