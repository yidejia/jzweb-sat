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
 * 龙存管操作SDK
 *
 * Class client
 * @package jzweb\sat\ccbll
 */
class Client
{
    public $config;

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
     * [fileUpload 文件上传]
     * @version <1.0>  2019-09-02T11:20:41+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function fileUpload($data)
    {
        return (new Merchant($this->config))->fileUpload($data);
    }

    /**
     * [personalAccount 个人用户开电子登记簿]
     * @version <1.0>  2019-09-06T11:16:12+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type] [description]
     */
    public function personalAccount($data, $h5 = false)
    {
        return (new Merchant($this->config))->personalAccount($data, $h5);
    }

    /**
     * [merchantAccount 企业用户开电子登记簿]
     * @version <1.0>   2019-09-06T11:21:00+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function merchantAccount($data, $h5 = false)
    {
        return (new Merchant($this->config))->merchantAccount($data, $h5);
    }

    /**
     * [personalInfoChange 个人用户信息变更]
     * @version <1.0>   2019-09-06T11:24:56+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function personalInfoChange($data, $h5 = false)
    {
        return (new Merchant($this->config))->personalInfoChange($data, $h5);
    }

    /**
     * [personalInfoChange 企业用户信息变更]
     * @version <1.0>   2019-09-06T11:24:56+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function merchantInfoChange($data, $h5 = false)
    {
        return (new Merchant($this->config))->merchantInfoChange($data, $h5);
    }

    /**
     * [accountStatusChange 用户电子登记簿状态变更]
     * @version <1.0>  2019-09-06T11:29:42+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function accountStatusChange($data)
    {
        return (new Merchant($this->config))->accountStatusChange($data);
    }

    /**
     * [passwordSetting 交易密码安全设置]
     * @version <1.0>  2019-09-06T11:40:00+0800
     * @return  [type] [description]
     */
    public function passwordSetting($data, $h5 = false)
    {
        return (new Merchant($this->config))->passwordSetting($data, $h5);
    }

    /**
     * [merchantCreateBatch 批量企业用户开户]
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function merchantCreateBatch($data)
    {
        return (new Merchant($this->config))->merchantCreateBatch($data);
    }

    /**
     * [merchantInfoChangeBatch 批量企业信息变更]
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function merchantInfoChangeBatch($data)
    {
        return (new Merchant($this->config))->merchantInfoChangeBatch($data);
    }

    /**
     * [personalBindingAndTopUp 个人绑定银行卡入金]
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function personalBindingAndTopUp($data, $h5 = false)
    {
        return (new Transfer($this->config))->personalBindingAndTopUp($data, $h5);
    }

    /**
     * [netSilverTopUp 网银入金]
     * @version <1.0>  2019-09-06T11:55:24+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function netSilverTopUp($data)
    {
        return (new Transfer($this->config))->netSilverTopUp($data);
    }

    /**
     * [withdraw 出金]
     * @version <1.0>   2019-09-06T14:26:06+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function withdraw($data, $h5 = false)
    {
        return (new Transfer($this->config))->withdraw($data, $h5);
    }

    /**
     * [platTopUp 平台电子登记簿入金]
     * @version <1.0>  2019-09-06T14:31:24+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platTopUp($data)
    {
        return (new Transfer($this->config))->platTopUp($data);
    }

    /**
     * [platWithdraw 平台电子登记簿出金]
     * @version <1.0>  2019-09-06T14:33:02+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platWithdraw($data)
    {
        return (new Transfer($this->config))->platWithdraw($data);
    }

    /**
     * [blceOrCardPay 余额支付/绑定卡支付]
     * @version <1.0>   2019-09-06T14:44:53+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function blceOrCardPay($data, $h5 = false)
    {
        return (new Trade($this->config))->blceOrCardPay($data, $h5);
    }

    /**
     * [anonyPay 匿名支付]
     * @version <1.0>  2019-09-06T14:49:28+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function anonyPay($data)
    {
        return (new Trade($this->config))->anonyPay($data);
    }

    /**
     * [anonyCardPay 银行卡匿名支付]
     * @version <1.0>  2019-09-06T14:49:28+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function anonyCardPay($data, $h5 = false)
    {
        return (new Trade($this->config))->anonyCardPay($data, $h5);
    }

    /**
     * [netSilverPay 网银支付]
     * @version <1.0>  2019-09-06T14:57:30+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function netSilverPay($data)
    {
        return (new Trade($this->config))->netSilverPay($data);
    }

    /**
     * [goodsNotice 商品发货通知]
     * @version <1.0>  2019-09-06T15:07:01+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function goodsNotice($data)
    {
        return (new Trade($this->config))->goodsNotice($data);
    }

    /**
     * [refund 退款申请]
     * @version <1.0>  2019-09-06T15:08:47+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function refund($data)
    {
        return (new Trade($this->config))->refund($data);
    }

    /**
     * [userToConfirm 用户确认]
     * @version <1.0>  2019-09-06T15:11:06+0800
     * @return  [type] [description]
     */
    public function userToConfirm($data, $h5 = false)
    {
        return (new Trade($this->config))->userToConfirm($data, $h5);
    }

    /**
     * [insteadToConfirm 超时后平台代为确认收货]
     * @version <1.0>  2019-09-06T15:14:58+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function insteadToConfirm($data)
    {
        return (new Trade($this->config))->insteadToConfirm($data);
    }

    /**
     * [smsOfPaySend 匿名支付交易确认短信发送]
     * @version <1.0>  2019-09-06T15:18:32+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function smsOfPaySend($data)
    {
        return (new Trade($this->config))->smsOfPaySend($data);
    }

    /**
     * [smsOfPayVerify 匿名支付交易确认短信验证]
     * @version <1.0>  2019-09-06T15:18:32+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function smsOfPayVerify($data)
    {
        return (new Trade($this->config))->smsOfPayVerify($data);
    }

    /**
     * [receiptToPrint 回单打印申请]
     * @version <1.0>  2019-09-06T15:41:57+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function receiptToPrint($data)
    {
        return (new Trade($this->config))->receiptToPrint($data);
    }

    /**
     * [platConfirmToRefund 平台确认退款申请]
     * @version <1.0>  2019-09-06T17:54:08+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platConfirmToRefund($data)
    {
        return (new Trade($this->config))->platConfirmToRefund($data);
    }

    /**
     * [userInfoQuery 用户信息查询]
     * @version <1.0>  2019-09-06T16:06:32+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function userInfoQuery($data)
    {
        return (new Query($this->config))->userInfoQuery($data);
    }

    /**
     * [acntBlceQueryH5 电子登记簿余额信息查询H5]
     * @version <1.0>   2019-09-06T16:15:11+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function acntBlceQueryH5($data, $h5 = false)
    {
        return (new Query($this->config))->acntBlceQueryH5($data, $h5);
    }

    /**
     * [acntBlceQuery 电子登记簿余额信息查询]
     * @version <1.0>   2019-09-06T16:15:11+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function acntBlceQuery($data)
    {
        return (new Query($this->config))->acntBlceQuery($data);
    }

    /**
     * [acntStatusQuery 电子登记簿状态信息查询]
     * @version <1.0>   2019-09-06T16:15:11+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5 [description]
     * @return  [type]                           [description]
     */
    public function acntStatusQuery($data)
    {
        return (new Query($this->config))->acntStatusQuery($data);
    }

    /**
     * [acntDetailQuery 电子登记簿收支明细查询]
     * @version <1.0>  2019-09-06T16:26:59+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function acntDetailQuery($data)
    {
        return (new Query($this->config))->acntDetailQuery($data);
    }

    /**
     * [tradeQuery 单笔交易查询]
     * @version <1.0>  2019-09-06T16:54:41+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function tradeQuery($data)
    {
        return (new Query($this->config))->tradeQuery($data);
    }

    /**
     * [tradeRecordsQuery 交易记录查询]
     * @version <1.0>  2019-09-06T16:56:33+0800
     * @return  [type] [description]
     */
    public function tradeRecordsQuery($data)
    {
        return (new Query($this->config))->tradeRecordsQuery($data);
    }

    /**
     * [butForOrderQuery 收款方待结算订单查询]
     * @version <1.0>  2019-09-06T17:14:09+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function butForOrderQuery($data)
    {
        return (new Query($this->config))->butForOrderQuery($data);
    }

    /**
     * [butForDetailQuery 收款方待结算交易明细查询(PC)]
     * @version <1.0>  2019-09-06T17:21:21+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function butForDetailQuery($data)
    {
        return (new Query($this->config))->butForDetailQuery($data);
    }

    /**
     * [platBlceQuery 平台电子登记簿余额信息查询]
     * @version <1.0>  2019-09-06T17:24:51+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platBlceQuery($data)
    {
        return (new Query($this->config))->platBlceQuery($data);
    }

    /**
     * [platRecordsQuery 平台电子登记簿收支明细查询]
     * @version <1.0>  2019-09-06T17:28:53+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platRecordsQuery($data)
    {
        return (new Query($this->config))->platRecordsQuery($data);
    }

    /**
     * [tradeConfirmQuery 交易确认查询]
     * @version <1.0>  2019-09-06T17:32:12+0800
     * @return  [type] [description]
     */
    public function tradeConfirmQuery($data)
    {
        return (new Query($this->config))->tradeConfirmQuery($data);
    }

    /**
     * [personalAcntQuery 个人银行账户列表查询]
     * @version <1.0>  2019-09-06T17:34:34+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function personalAcntQuery($data)
    {
        return (new Query($this->config))->personalAcntQuery($data);
    }

    /**
     * [fenrunOrderQuery 分润订单列表查询]
     * @version <1.0>  2019-09-06T17:43:40+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function fenrunOrderQuery($data)
    {
        return (new Query($this->config))->fenrunOrderQuery($data);
    }

    /**
     * [payAuthInfoQuery 打款认证信息查询]
     * @version <1.0>  2019-09-06T17:48:23+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function payAuthInfoQuery($data)
    {
        return (new Query($this->config))->payAuthInfoQuery($data);
    }

    /**
     * [refundQuery 退款交易查询]
     * @version <1.0>  2019-09-06T17:55:41+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function refundQuery($data)
    {
        return (new Query($this->config))->refundQuery($data);
    }

    /**
     * [receiptToPrintQuery 回单打印申请查询]
     * @version <1.0>  2019-09-06T17:50:02+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function receiptToPrintQuery($data)
    {
        return (new Query($this->config))->receiptToPrintQuery($data);
    }

    /**
     * [payResNotice 匿名支付结果通知]
     * @version <1.0>  2019-09-06T16:01:29+0800
     * @return  [type] [description]
     */
    public function payResNotice($data)
    {
        return (new Notice($this->config))->payResNotice($data);
    }

    public function asynchroNotice($data)
    {
        return (new Notice($this->config))->asynchroNotice($data);
    }

    /**
     * [checkFileToDownload 对账文件下载]
     * @version <1.0>  2019-09-06T15:57:14+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function checkFileToDownload($data)
    {
        return (new Check($this->config))->checkFileToDownload($data);
    }

    /**
     * [checkFileToDownloadPC 对账文件下载]
     * @version <1.0>  2019-09-06T15:57:14+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function checkFileToDownloadPC($data)
    {
        return (new Check($this->config))->checkFileToDownloadPC($data);
    }

    /**
     * [platKeyUpload 平台密钥上传]
     * @version <1.0>  2019-09-06T15:50:07+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platKeyUpload($data)
    {
        return (new KeyManage($this->config))->platKeyUpload($data);
    }

    /** 用户向平台缴费 */
    public function usersPayCostToPlat()
    {
        return (new Trade($this->config))->usersPayCostToPlat($data);
    }

    /** 用户向平台缴费(H5) */
    public function usersPayCostToPlatH5($data, $h5=false)
    {
        return (new Trade($this->config))->usersPayCostToPlatH5($data, $h5);
    }
}
