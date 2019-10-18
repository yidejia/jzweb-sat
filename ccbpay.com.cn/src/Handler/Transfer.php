<?php

namespace jzweb\sat\ccbpay\Handler;

use jzweb\sat\ccbpay\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 出入金类
 * 移动H5,PC端接口用H5地址，API接口用api地址
 * @author changge(1282350001@qq.com)
 */
class Transfer extends BaseRequest
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
     * [personalBindingAndTopUp 个人绑定银行卡入金]
     *
     * R1.用户实际到账金额=入金金额-平台手续费，如果存管交易平台代收渠道手续费设置为收取用户，
     * 则用户实际到账金额=入金金额-平台手续费-代收渠道收费，平台手续费+代收渠道收费不得超过入金金额。
     *
     * @version <1.0>  2019-09-06T11:29:42+0800
     */
    public function personalBindingAndTopUp($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('300001', $data) : $this->httpRequest->h5Post('310001', $data);
    }

    /**
     * [netSilverTopUp 网银入金]
     *
     * R1. 用户实际到账金额=入金金额-平台手续费，如果存管交易平台代收渠道手续费设置为收取用户，则用户实际到账金额=入金金额-平台手续费-代收渠道收费，平台手续费+代收渠道收费不得超过入金金额；
     * R2.持卡人付款成功后，支付网关接收到银行支付结果，服务端将支付结果通知给商户，有两种通知方式：页面通知和后台通知；
     * R3.商户上送订单支付请求中有receiveUrl(商户上送的后台通知地址)和pickupUrl(商户上送的页面通知地址)两字段，如果两个参数都填写，则服务端后台通知到 receiveUrl 地址，页面跳转方式通知到 pickupUrl 地址；
     * R4.银行账户支付类型分为：1:个人储蓄卡网银支付、2:企业网银支付；
     * R5.根据支付公司的接口要求，需要上传商品名称，可直接传入金；
     * R6.异步通知，仅在支付成功时通知商户
     *
     * @version <1.0>  2019-09-06T11:55:24+0800
     */
    public function netSilverTopUp($data)
    {
        return $this->httpRequest->apiPost('300006', $data);
    }

    /**
     * [withdraw 出金]
     *
     * R1.用户实际到账金额=出金金额-平台手续费，平台手续费不得超过出金金额；
     * R2.业务类型包括:19001:个人用户出金、19002:企业用户出金。
     *
     * @version <1.0>   2019-09-06T14:26:06+0800
     */
    public function withdraw($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('300002', $data) : $this->httpRequest->h5Post('310002', $data);
    }

    /**
     * [platTopUp 平台电子登记簿入金]
     *
     * R1.电子登记簿类型：02:手续费支出电子登记簿，03:营销电子登记簿；
     * R2.实时返回入金结果，如果为受理中，需通过单笔交易查询接口查询入金结果。
     *
     * @version <1.0>  2019-09-06T14:31:24+0800
     */
    public function platTopUp($data)
    {
        return $this->httpRequest->apiPost('300009', $data);
    }

    /**
     * [platWithdraw 平台电子登记簿出金]
     *
     * R1.电子登记簿类型：01:手续费收入电子登记簿，02:手续费支出电子登记簿，03:营销电子登记簿，04:代收渠道手续费电子登记簿；
     * R2.出金请求成功发起后，接口实时返回出金的受理结果，实际出金结果需通过单笔交易查询接口返回的查询信息为准，建议每15分钟查询一次
     *
     * @version <1.0>  2019-09-06T14:33:02+0800
     */
    public function platWithdraw($data)
    {
        return $this->httpRequest->apiPost('300010', $data);
    }
}