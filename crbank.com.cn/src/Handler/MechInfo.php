<?php

namespace jzweb\sat\crbank\Handler;

/**
 * 商户注册查询
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/6
 */
class  MechInfo extends BankRequest
{

    //查询商户地址
    const queryMchUrl = "/pay/mch/query";
    //查询商户账户详细地址
    const queryAccountUrl = "/pay/account/query";
    //使用此接口可以将激活状态的账户改变为销户状态
    const cancelAccountUrl = "/pay/account/cancel";
    //使用接口可以冻结商户余额等数据
    const freezeAccountUrl = "/pay/account/freeze";
    //使用接口可以解冻商户余额等数据
    const thawAccountUrl = "/pay/account/thaw";
    const acntFlowUrl = "/pay/acntflow/query";

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
     * 查询商户信息
     * 第一次使用外部上好查询商户信息
     *
     * @param string $mch_no 商户编号
     * @param bool $is_out 标记是否是外部商户号
     * @return string
     */
    public function mechQuery($mch_no, $is_out = true)
    {
        $data['mch_no'] = $mch_no;
        $data['out_mch_no'] = "1";
        return $this->httpRequest->post(self::queryMchUrl, $data);
    }

    /**
     * 查询商户的账户详细信息
     *
     * @param string $mch_no 存管系统端子商户号
     * @param string $currency 币种
     *
     * @return string
     */
    public function accountQuery($mch_no, $currency = "CNY")
    {
        $data['mch_no'] = $mch_no;
        $data['currency'] = $currency;
        return $this->httpRequest->post(self::queryAccountUrl, $data);
    }

    /**
     * 使用此接口可以将激活状态的账户改变为销户状态
     * todo 该接口暂时不用调用
     *
     * @param string $mch_no 存管系统端子商户号
     * @return string
     */
    public function cancelAccount($mch_no)
    {
        $data['mch_no'] = $mch_no;
        return $this->httpRequest->post(self::cancelAccountUrl, $data);
    }

    /**
     * 商户余额冻结
     *
     * @param string $mch_no 子商户号
     * @param float $amount 解冻金额
     * @param string $brief 摘要备注信息
     * @param string $currency
     *
     * @return array
     */
    public function freezeAccount($mch_no, $amount, $brief, $currency = "CNY")
    {
        $data['mch_no'] = $mch_no;
        $data['currency'] = $currency;
        $data['brief'] = $brief;
        $data['amount'] = $amount * 10 * 10;
        return $this->httpRequest->post(self::freezeAccountUrl, $data);
    }

    /**
     * 商户余额解冻
     *
     * @param string $mch_no 子商户号
     * @param float $amount 解冻金额
     * @param string $brief 摘要备注信息
     * @param string $currency
     *
     * @return array
     */
    public function thawAccount($mch_no, $amount, $brief, $currency = "CNY")
    {
        $data['mch_no'] = $mch_no;
        $data['currency'] = $currency;
        $data['brief'] = $brief;
        $data['amount'] = $amount * 10 * 10;
        return $this->httpRequest->post(self::thawAccountUrl, $data);
    }

    /**
     * 商户交易详细流水记录
     * @param $mch_no 子商户号
     * @param $start_time 记账开始时间 格式:yyyyMMddHHmmss
     * @param $end_time  记账结束时间
     * @param $page 第几页
     * @param int $limit 每页条数,不能超1000
     */
    public function acntFlowQuery($mch_no,$start_time,$end_time,$page=1,$limit=900){
        $data['mch_no']=$mch_no;
        $data['start_time'] = str_replace(['-',':',' '],'',$start_time);
        $data['end_time'] = str_replace(['-',':',' '],'',$end_time);
        $data['page']=$page;
        $data['limit'] = $limit>900?900:$limit;
        return $this->httpRequest->post(self::acntFlowUrl, $data);

    }

    /**
     * 商户批量进件操作
     *
     * @param string $file 要上传的文件名称
     * @return string
     */
    public function batchRegister($file)
    {
        return $this->sftpRequest->upload($file,true);
    }

    /**
     * 获取批量操作结果
     *
     * @param string $dir
     * @param string $file
     * @return string
     */
    public function getBatchResp($dir, $file)
    {
        return $this->sftpRequest->resp($dir, $file);
    }


}
