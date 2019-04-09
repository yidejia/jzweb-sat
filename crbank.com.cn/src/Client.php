<?php

namespace jzweb\sat\crbank;

use jzweb\sat\crbank\Handler\Auth;
use jzweb\sat\crbank\Handler\CheckBill;
use jzweb\sat\crbank\Handler\MechInfo;
use jzweb\sat\crbank\Handler\MechSettle;
use jzweb\sat\crbank\Handler\OrderTrade;
use jzweb\sat\crbank\Handler\WithDraws;

/**
 * 封装分账宝操作SDK
 *
 * Class client
 * @package jzweb\sat\crbank
 */
class Client
{
    private $config;

    /**
     * 构造函数
     *
     * client constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 查询商户信息
     *
     * @param string $mch_no
     * @param bool $is_out
     *
     * @return string
     */
    public function mechQuery($mch_no, $is_out = true)
    {
        return (new MechInfo($this->config))->mechQuery($mch_no, $is_out);
    }

    /**
     * 商户流水
     * @param $mch_no
     * @param $start_time
     * @param $end_time
     * @param $page
     * @param $limit
     * @return string
     */
    public function flowQuery($mch_no, $start_time,$end_time,$page,$limit)
    {
        return (new MechInfo($this->config))->acntFlowQuery($mch_no,$start_time,$end_time,$page,$limit);
    }

    /**
     * 查询商户账户详情
     *
     * @param string $mch_no
     *
     * @return string
     */
    public function accountQuery($mch_no)
    {
        return (new MechInfo($this->config))->accountQuery($mch_no);
    }

    /**
     * 注销账户
     *
     * @param string $mch_no
     *
     * @return string
     */
    public function cancelAccount($mch_no)
    {
        return (new MechInfo($this->config))->cancelAccount($mch_no);
    }

    /**
     * 商户批量进件
     *
     * @return string
     */
    public function mechBatchRegister($file)
    {
        return (new MechInfo($this->config))->batchRegister($file);
    }

    /**
     * 商户批量进件
     *
     * @return string
     */
    public function getBatchResp($dir, $file)
    {
        return (new MechInfo($this->config))->getBatchResp($dir, $file);
    }

    /**
     * 批量订单
     * @param $file
     * @return string
     */
    public function batchOrders($file)
    {
        return (new OrderTrade($this->config))->batOrderOrTrade($file);
    }

    /**
     * 批量订单 -- 处理过期数据
     * @param $file
     * @param $date
     * @return string
     */
    public function batchOverOrders($file, $date)
    {
        return (new OrderTrade($this->config))->batOverOrderOrTrade($file, $date);
    }

    /**
     * 获取批量订单信息上传结果
     * @param $dir
     * @param $file
     * @return string
     */
    public function getOrderBatchResp($dir, $file)
    {
        return (new OrderTrade($this->config))->getBatchResp($dir, $file);
    }

    /**
     * 批量交易(银行明细)
     * @param $file
     * @return string
     */
    public function batchTrade($file)
    {
        return (new OrderTrade($this->config))->batOrderOrTrade($file);
    }

    /**
     * 批量交易(银行明细) -- 处理过期数据
     * @param $file
     * @param $date
     * @return string
     */
    public function batchOverTrade($file, $date)
    {
        return (new OrderTrade($this->config))->batOverOrderOrTrade($file, $date);
    }


    /**
     * 批量提现请求
     * @param $file
     * @return string
     */
    public function batchWithDraws($file)
    {
        return (new WithDraws($this->config))->batWithDraws($file);
    }

    /**
     * 获取批量提现请求的响应
     * @param $dir
     * @param $file
     * @return string
     */
    public function getWithDrawsBatchResp($dir, $file)
    {
        return (new WithDraws($this->config))->getWithDrawsResp($dir, $file);
    }


    /**
     * 商户结算
     *
     * @param string $mch_no 商户编号
     * @param string $out_trade_no 外部商户单号,必须保持唯一
     * @param int $amount 结算金额,单位是分,必须是纯数字
     * @param date $book_date 记账日期
     * @param string $beief 备注信息,记账摘要
     * @param string $currency 交易币种,默认是人民币CNY
     * @return array
     */
    public function mechSettle($mch_no, $out_trade_no, $amount, $book_date, $brief = "", $currency = "CNY")
    {
        return (new MechSettle($this->config))->settle($mch_no, $out_trade_no, $amount, $book_date, $brief, $currency);
    }

    /**
     * 提现之前的鉴权
     * @param $data
     * @return string
     */
    public function startAuth($data)
    {
        return (new Auth($this->config))->startAuth($data);
    }

    /**
     * 批量结算请求
     * @param $file
     * @return string
     */
    public function batchSettle($file){
        return (new MechSettle($this->config))->batSettle($file);
    }

    /**
     * 获取批量提现请求的响应
     * @param $dir
     * @param $file
     * @return string
     */
    public function getBatSettleResp($dir, $file)
    {
        return (new MechSettle($this->config))->getBatSettleResp($dir, $file);
    }

    /**
     * 下载对账单
     * @param $dir
     * @param $file
     * @return string
     */
    public function downLoadBillData($dir, $file){
        return (new OrderTrade($this->config))->downLoadBillData($dir,$file);
    }


    /**
     * 01：订单正常未入账
     * @param $data
     * @return array
     */
    public function adjust01($data){
        return (new CheckBill($this->config))->adjust01($data['mch_no'],$data['out_trade_no'],$data['trade_time'],$data['out_order_no']);
    }


    /**
     * 02：订单交易金额与分账金额不一致
     * @param $mch_no
     * @param $divide_plat_no
     * @param $divide_mch_no
     * @param $out_trade_no
     * @param $total_fee
     * @param $divide_plat_amount
     * @param $divide_mch_amount
     * @param $proce_fee
     * @param $trade_time
     * @param $pay_time
     * @param $out_order_no
     * @return array
     */
    public function  adjust02($mch_no, $divide_plat_no, $divide_mch_no, $out_trade_no, $total_fee, $divide_plat_amount, $divide_mch_amount, $proce_fee, $trade_time, $pay_time, $out_order_no){
        return (new CheckBill($this->config))-> adjust02($mch_no, $divide_plat_no, $divide_mch_no, $out_trade_no, $total_fee, $divide_plat_amount, $divide_mch_amount, $proce_fee, $trade_time, $pay_time, $out_order_no );
    }

    /**
     *03：跨天账单调账
     * @param $mch_no
     * @param $out_trade_no
     * @param $trade_time
     * @param $pay_time
     * @param string $out_order_no
     * @return array
     */
    public function adjust03($mch_no, $out_trade_no, $trade_time, $pay_time, $out_order_no = ""){
        return (new CheckBill($this->config))->adjust03($mch_no, $out_trade_no, $trade_time, $pay_time, $out_order_no);
    }

    /**
     * 04：订单漏记
     * @param $data
     * @return array
     */
    public function adjust04($data){

        return (new CheckBill($this->config))->adjust04($data['mch_no'], $data['divide_plat_no'], $data['divide_mch_no'], $data['out_trade_no'], $data['transaction_id'], $data['trade_money'], $data['refund_money'], $data['trade_state'], $data['proce_fee'], $data['trade_time'], $data['pay_time'], $data['total_fee'], $data['divide_plat_amount'], $data['divide_mch_amount'], $data['currency'], $data['refund_transaction_id'] , $data['out_refund_trade_no'], $data['out_order_no'], $data['out_refund_no'] );
    }




}