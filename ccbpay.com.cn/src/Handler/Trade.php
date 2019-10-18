<?php

namespace jzweb\sat\ccbpay\Handler;

use jzweb\sat\ccbpay\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 交易类
 * 移动H5,PC端接口用H5地址，API接口用api地址
 * @author changge(1282350001@qq.com)
 */
class Trade extends BaseRequest
{
    /**
     * [__construct 构造函数]
     * @version <1.0>  2019-09-02T11:08:48+0800
     * @param   [type] $config                  [description]
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * [blceOrCardPay 余额支付/绑定卡支付]
     *
     * 用户通过该接口在平台页面上选择使用开立的电子登记簿及绑定的银行卡进行电子登记簿余额支付、绑定银行卡支付，
     * 再跳转至银行存管页面完成支付。该接口与余额支付/绑定卡支付（银行端收银台选择）
     * 区别在于用户是在平台页面还是跳转至存管银行页面后选择支付方式。
     *
     * @version <1.0>   2019-09-06T14:44:53+0800
     */
    public function blceOrCardPay($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('200010', $data) : $this->httpRequest->h5Post('210010', $data);
    }

    /**
     * [anonyPay 匿名支付]
     *
     * 用户通过该接口在平台页面上选择：微信跳转、微信公众号、微信小程序、微信扫码、支付宝扫码、银联支付等支付方式，对交易订单进行支付。
     *
     * @version <1.0>  2019-09-06T14:49:28+0800
     */
    public function anonyPay($data)
    {
        return $this->httpRequest->apiPost('200009', $data);
    }

    /**
     * [anonyCardPay 银行卡匿名支付]
     *
     * 用户通过该接口在平台上选择：借记卡或贷记卡支付方式，跳转至存管银行业务完成支付。
     *
     * @version <1.0>  2019-09-06T14:49:28+0800
     */
    public function anonyCardPay($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('200017', $data) : $this->httpRequest->h5Post('210017', $data);
    }

    /**
     * [netSilverPay 网银支付]
     *
     * 用户使用PC端在平台发起支付请求，跳转至所选择银行的网关页面完成订单支付，支持个人、企业网银消费请求。
     *
     * @version <1.0>  2019-09-06T14:57:30+0800
     */
    public function netSilverPay($data)
    {
        return $this->httpRequest->h5Post('300007', $data);
    }

    /**
     * [goodsNotice 商品发货通知]
     *
     * 卖家在对订单进行发货后，通过该接口在平台上向存管系统发送该订单的发货通知，
     * 也即是进行商城收货确认申请。只有进行了发货通知的订单，消费者才可以进行收货确认。
     *
     * @version <1.0>  2019-09-06T15:05:29+0800
     */
    public function goodsNotice($data)
    {
        return $this->httpRequest->apiPost('200004', $data);
    }

    /**
     * [refund 退款申请]
     *
     * 平台通过API接口发起全额退款、部分退款申请
     *
     * @version <1.0>  2019-09-06T15:08:47+0800
     */
    public function refund($data)
    {
        return $this->httpRequest->apiPost('200007', $data);
    }

    /**
     * [userToConfirm 用户确认]
     *
     * 用户通过平台跳转至存管银行页面，进行商城收货确认、退款确认、佣金退款等确认。
     * 其中商城收货确认适用于买家、退款确认适用于卖家、佣金退款适用于分佣方。
     *
     * @version <1.0>  2019-09-06T15:11:06+0800
     */
    public function userToConfirm($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('200006', $data) : $this->httpRequest->h5Post('210006', $data);
    }

    /**
     * [insteadToConfirm 超时后平台代为确认收货]
     * 对于商城收货确认时，如果超过一定期限消费者仍未进行收货确认，则平台可通过该接口代消费者发起确认收货操作。
     * @version <1.0>  2019-09-06T15:14:58+0800
     */
    public function insteadToConfirm($data)
    {
        return $this->httpRequest->apiPost('200008', $data);
    }

    /**
     * [smsOfPaySend 匿名支付交易确认短信发送]
     * 如消费者未开立电子登记簿，使用匿名支付且留手机号时，可通过该接口申请收货确认短信发送。
     * @version <1.0>  2019-09-06T15:18:32+0800
     */
    public function smsOfPaySend($data)
    {
        return $this->httpRequest->apiPost('200024', $data);
    }

    /**
     * [smsOfPayVerify 匿名支付交易确认短信验证]
     * 用户输入短信验证码，完成交易确认验证。
     * @version <1.0>  2019-09-06T15:18:32+0800
     */
    public function smsOfPayVerify($data)
    {
        return $this->httpRequest->apiPost('200025', $data);
    }

    /**
     * [receiptToPrint 回单打印申请]
     * 用户或平台通过该接口向存管银行发起交易回单打印申请。
     * 存管银行接受申请后，目前暂以纸质回单的方式邮寄给客户。
     * @version <1.0>  2019-09-06T15:41:57+0800
     */
    public function receiptToPrint($data)
    {
        return $this->httpRequest->apiPost('700013', $data);
    }

    /**
     * [platConfirmToRefund 平台确认退款申请]
     * @version <1.0>  2019-09-06T17:54:08+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platConfirmToRefund($data)
    {
        return $this->httpRequest->apiPost('200028', $data);
    }
}