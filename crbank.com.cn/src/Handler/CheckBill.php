<?php

namespace jzweb\sat\crbank\Handler;

/**
 * 下载账单&对账&补/调单
 *
 * 平台商在下载分账宝对账之后发现账单漏记、未入账、金额不一致、账期不一致可以根据具体类型进行响应的补调账。
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/6
 */
class  CheckBill extends BankRequest
{

    //补/调单
    const adjustUrl = "/pay/trade/adjust";

    /**
     * 构造函数
     *
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * 对账单下载
     *
     * @return string
     */
    public function downloadBill()
    {
        return "";
    }


    /**
     * 补调单01接口
     * 订单正常未入账
     *
     * @param $mch_no              商户号
     * @param $out_trade_no        商户支付单号
     * @param $trade_time          交易时间
     * @param string $out_order_no 商户订单号,可选
     *
     * @return array
     */
    public function adjust01($mch_no, $out_trade_no, $trade_time, $out_order_no = "")
    {
        $data['mch_no'] = $mch_no;
        $data['out_trade_no'] = $out_trade_no;
        $out_order_no && $data['out_order_no'] = $out_order_no;
        $data['adjust_type'] = "01";
        $data['trade_time'] = $trade_time;

        return $this->httpRequest->post(self::adjustUrl, $data);
    }

    /**
     * 补调单02接口
     * 订单交易金额与分账金额不一致
     *
     * @param string $mch_no 商户号
     * @param string $divide_plat_no 平台商编号
     * @param string $divide_mch_no 商户编号
     * @param string $out_trade_no 商户支付单号
     * @param float $total_fee 支付金额
     * @param float $divide_plat_amount 平台上分账金额
     * @param float $divide_mch_amount 商户分账金额
     * @param float $proce_fee 手续费
     * @param string $trade_time 交易时间
     * @param string $pay_time 支付完成时间
     * @param string $out_order_no 商户订单号
     *
     * @return array
     */
    public function adjust02($mch_no, $divide_plat_no, $divide_mch_no, $out_trade_no, $total_fee, $divide_plat_amount, $divide_mch_amount, $proce_fee, $trade_time, $pay_time, $out_order_no = "")
    {
        $data['mch_no'] = $mch_no;
        $data['out_trade_no'] = $out_trade_no;
        $out_order_no && $data['out_order_no'] = $out_order_no;
        $data['adjust_type'] = "02";
        $data['proce_fee'] = $proce_fee;
        $data['trade_time'] = $trade_time;
        $data['pay_time'] = $pay_time;
        $data['total_fee'] = $total_fee;
        $data['divide_plat_no'] = $divide_plat_no;
        $data['divide_plat_amount'] = $divide_plat_amount;
        $data['divide_mch_no'] = $divide_mch_no;
        $data['divide_mch_amount'] = $divide_mch_amount;

        return $this->httpRequest->post(self::adjustUrl, $data);
    }

    /**
     * 补调单03接口
     * 跨天账单调单
     *
     * @param string $mch_no 商户号
     * @param string $out_trade_no 商户支付单号
     * @param string $trade_time 交易时间
     * @param string $pay_time 支付完成时间
     * @param string $out_order_no 商户订单号
     *
     * @return array
     */
    public function adjust03($mch_no, $out_trade_no, $trade_time, $pay_time, $out_order_no = "")
    {
        $data['mch_no'] = $mch_no;
        $data['out_trade_no'] = $out_trade_no;
        $out_order_no && $data['out_order_no'] = $out_order_no;
        $data['adjust_type'] = "03";
        $data['trade_time'] = $trade_time;
        $data['pay_time'] = $pay_time;

        return $this->httpRequest->post(self::adjustUrl, $data);
    }

    /**
     * 补调单04接口
     * 订单漏记
     *
     * @param string $mch_no 商户号
     * @param string $divide_plat_no 平台商编号
     * @param string $divide_mch_no 商户编号
     * @param string $out_trade_no 商户支付单号
     * @param string $transaction_id 上有订单号
     * @param float $trade_money 交易金额
     * @param float $refund_money 退款金额
     * @param string $trade_state 交易状态 2：支付成功 4: 退款成功
     * @param float $proce_fee 手续费
     * @param date $trade_time 交易时间
     * @param date $pay_time 支付完成时间
     * @param float $total_fee 支付金额
     * @param float $divide_plat_amount 平台商分账金额
     * @param float $divide_mch_amount 商户分账金额
     * @param string $currency 交易币种
     * @param string $refund_transaction_id 上游退款单号
     * @param string $out_refund_trade_no 商户退款支付单号
     * @param string $out_order_no 商户订单号
     * @param string $out_refund_no 商户退款单号
     *
     * @return array
     */
    public function adjust04($mch_no, $divide_plat_no, $divide_mch_no, $out_trade_no, $transaction_id, $trade_money, $refund_money, $trade_state, $proce_fee, $trade_time, $pay_time, $total_fee, $divide_plat_amount, $divide_mch_amount, $currency = "CNY", $refund_transaction_id = "", $out_refund_trade_no = "", $out_order_no = "", $out_refund_no = "")
    {
        $data['mch_no'] = $mch_no;
        $data['out_trade_no'] = $out_trade_no;
        $out_order_no && $data['out_order_no'] = $out_order_no;
        $data['transaction_id'] = $transaction_id;
        $out_refund_trade_no && $data['out_refund_trade_no'] = $out_refund_trade_no;
        $out_refund_no && $data['out_refund_no'] = $out_refund_no;
        $refund_transaction_id && $data['refund_transaction_id'] = $refund_transaction_id;
        $data['currency'] = $currency;
        $data['trade_money'] = $trade_money * 10 * 10;
        $data['refund_money'] = $refund_money * 10 * 10;
        $data['trade_state'] = $trade_state;
        $data['adjust_type'] = "04";
        $data['trade_type'] = "04";
        $data['procs_fee'] = $proce_fee;
        $data['trade_time'] = $trade_time;
        $data['pay_time'] = $pay_time;
        $data['total_fee'] = $total_fee;
        $data['divide_plat_no'] = $divide_plat_no;
        $data['divide_plat_amount'] = $divide_plat_amount;
        $data['divide_mch_no'] = $divide_mch_no;
        $data['divide_mch_amount'] = $divide_mch_amount;

        return $this->httpRequest->post(self::adjustUrl, $data);
    }
}
