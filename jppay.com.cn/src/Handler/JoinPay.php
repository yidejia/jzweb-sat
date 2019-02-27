<?php

namespace jzweb\sat\jppay\Handler;

/**
 * 聚合支付
 *
 * 支付宝+微信+APP+小程序+H5...
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2019/2/15
 */
class  JoinPay extends PayRequest
{

    /**
     * 构造函数
     *
     * JoinPay constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * 回调验签
     *
     * @param string $xml
     * @return array|bool|mixed
     * @throws \jzweb\sat\jppay\Exception\ServerException
     */
    public function verifySignCallBack($xml)
    {
        $data = $this->httpRequest->fromXml($xml);
        if ($data && $data['result_code'] == "SUCCESS" && $data['return_code'] == "SUCCESS") {
            $sign = $data['sign'];
            unset($data['sign']);
            if ($sign == $this->httpRequest->buildSign($data)) {
                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 统一支付接口
     *
     * @param $params
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function unifiedorder($params)
    {
        return $this->httpRequest->post("/pay/unifiedorder", $params);

    }

    /**
     * 订单支付查询接口
     *
     * @param string $out_trade_no 商户订单号
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function orderQuery($out_trade_no)
    {
        //商户系统内部的订单号
        $params['out_trade_no'] = $out_trade_no;
        return $this->httpRequest->post("/pay/orderquery", $params);
    }


    /**
     * 申请退款
     *
     * @param string $out_trade_no
     * @param string $out_refund_no
     * @param int $total_fee
     * @param int $refund_fee
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefund($out_trade_no, $out_refund_no, $total_fee, $refund_fee)
    {
        //商户系统内部的订单号
        $params['out_trade_no'] = $out_trade_no;
        //商户退款单号
        $params['out_refund_no'] = $out_refund_no;
        //订单总金额
        $params['total_fee'] = $total_fee;
        //退款总金额
        $params['refund_fee'] = $refund_fee;
        return $this->httpRequest->post("/secapi/pay/refund", $params);
    }

    /**
     * 查询退款单号
     *
     * @param string $out_trade_no
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefundQuery($out_trade_no)
    {
        $params['out_trade_no'] = $out_trade_no;
        return $this->httpRequest->post("/pay/refundquery", $params);
    }


}