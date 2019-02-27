<?php

namespace jzweb\sat\crbank\Handler;

/**
 * 商户结算
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/6
 */
class  MechSettle extends BankRequest
{

    //查询商户地址
    const settleUrl = "/pay/mch/settle";

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
     * 平台商可以对平台商和商户进行按金额结算
     *
     * @param string $mch_no 商户编号
     * @param string $out_trade_no 外部商户单号,必须保持唯一
     * @param int $amount 结算金额,单位是分,必须是纯数字
     * @param date $book_date 记账日期
     * @param string $beief 备注信息,记账摘要
     * @param string $currency 交易币种,默认是人民币CNY
     *
     * @return array
     */
    public function settle($mch_no, $out_trade_no, $amount, $book_date, $brief = "", $currency = "CNY")
    {
        $data['mch_no'] = $mch_no;
        $data['out_trade_no'] = $out_trade_no;
        $data['brief'] = $brief;
        $data['currency'] = $currency;
        $data['book_date'] = $book_date;
        $data['amount'] = $amount * 10 * 10;

        return $this->httpRequest->post(self::settleUrl, $data);
    }

    /**
     * 批量结算
     * @param $file
     * @return string
     */
    public function batSettle($file) {
        return $this->sftpRequest->upload($file);
    }

    /**
     * 获取批量结算的操作结果
     *
     * @param string $dir
     * @param string $file
     * @return string
     */
    public function getBatSettleResp($dir, $file) {
        return $this->sftpRequest->resp($dir, $file);
    }

}
