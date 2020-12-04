<?php

namespace jzweb\sat\ccbpay\Handler;

use jzweb\sat\ccbpay\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 查询类
 * 移动H5,PC端接口用H5地址，API接口用api地址
 * @author changge(1282350001@qq.com)
 */
class Query extends BaseRequest
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
     * [userInfoQuery 用户信息查询]
     * 查询用户信息
     * @version <1.0>  2019-09-06T16:06:32+0800
     */
    public function userInfoQuery($data)
    {
        return $this->httpRequest->apiPost('400001', $data);
    }

    /**
     * [acntBlceQueryH5 电子登记簿余额信息查询H5]
     * 查询账户余额等相关信息
     * @version <1.0>   2019-09-06T16:15:11+0800
     */
    public function acntBlceQueryH5($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('400002', $data) : $this->httpRequest->h5Post('410002', $data);
    }

    /**
     * [acntBlceQuery 电子登记簿余额信息查询]
     * 查询账户余额等相关信息
     * @version <1.0>   2019-09-06T16:15:11+0800
     */
    public function acntBlceQuery($data)
    {
        return $this->httpRequest->apiPost('400003', $data);
    }

    /**
     * [acntDetailQuery 电子登记簿收支明细查询]
     * 根据交易日期范围，按分页查询指定用户的账户收支明细。
     * @version <1.0>  2019-09-06T16:25:50+0800
     */
    public function acntDetailQuery($data)
    {
        return $this->httpRequest->apiPost('400007', $data);
    }

    /**
     * [acntStatusQuery 电子登记簿状态信息查询]
     * 查询账户余额等相关信息
     * @version <1.0>   2019-09-06T16:15:11+0800
     */
    public function acntStatusQuery($data)
    {
        return $this->httpRequest->apiPost('400004', $data);
    }

    /**
     * [tradeQuery 单笔交易查询]
     * 查询单笔交易明细信息
     * @version <1.0>  2019-09-06T16:54:41+0800
     */
    public function tradeQuery($data)
    {
        return $this->httpRequest->apiPost('400005', $data);
    }

    /**
     * [tradeRecordsQuery 交易记录查询]
     * 根据交易日期时间范围、业务类型查询，账户交易明细信息。
     * @version <1.0>  2019-09-06T16:56:33+0800
     */
    public function tradeRecordsQuery($data)
    {
        return $this->httpRequest->apiPost('400006', $data);
    }

    /**
     * [butForOrderQuery 收款方待结算订单查询]
     * 根据交易日期范围，按分页查询指定卖家的待结算订单信息
     * @version <1.0>  2019-09-06T17:14:09+0800
     */
    public function butForOrderQuery($data)
    {
        return $this->httpRequest->apiPost('400008', $data);
    }

    /**
     * [butForDetailQuery 收款方待结算交易明细查询(PC)]
     * 查询收款方待结算交易明细。
     * @version <1.0>  2019-09-06T17:21:21+0800
     */
    public function butForDetailQuery($data)
    {
        return $this->httpRequest->h5Post('410010', $data);
    }

    /**
     * [platBlceQuery 平台电子登记簿余额信息查询]
     * 查询平台电子登记簿余额信息
     * @version <1.0>  2019-09-06T17:24:51+0800
     */
    public function platBlceQuery($data)
    {
        return $this->httpRequest->apiPost('P2P024', $data);
    }

    /**
     * [platRecordsQuery 平台电子登记簿收支明细查询]
     * 根据交易日期范围，按分页查询指定平台的电子登记簿收支明细。
     * @version <1.0>  2019-09-06T17:28:53+0800
     */
    public function platRecordsQuery($data)
    {
        return $this->httpRequest->apiPost('400015', $data);
    }

    /**
     * [tradeConfirmQuery 交易确认查询]
     * 查询交易确认状态
     * @version <1.0>  2019-09-06T17:32:12+0800
     */
    public function tradeConfirmQuery($data)
    {
        return $this->httpRequest->apiPost('400018', $data);
    }

    /**
     * [personalAcntQuery 个人银行账户列表查询]
     * 查询个人绑定的银行账户列表信息。
     * @version <1.0>  2019-09-06T17:34:34+0800
     */
    public function personalAcntQuery($data)
    {
        return $this->httpRequest->apiPost('400019', $data);
    }

    /**
     * [fenrunOrderQuery 分润订单列表查询]
     * 查询订单的分润信息
     * @version <1.0>  2019-09-06T17:43:40+0800
     */
    public function fenrunOrderQuery($data)
    {
        return $this->httpRequest->apiPost('400031', $data);
    }

    /**
     * [payAuthInfoQuery 打款认证信息查询]
     * 查询平台用户对公账户打款认证金额及平台收款账户信息
     * @version <1.0>  2019-09-06T17:48:23+0800
     */
    public function payAuthInfoQuery($data)
    {
        return $this->httpRequest->apiPost('400032', $data);
    }

    /**
     * [refundQuery 退款交易查询]
     * 通过存管交易平台退款订单编号查询退款交易订单信息
     * @version <1.0>  2019-09-06T17:55:41+0800
     */
    public function refundQuery($data)
    {
        return $this->httpRequest->apiPost('400038', $data);
    }

    /**
     * [receiptToPrintQuery 回单打印申请查询]
     * 查询回单打印申请的进度
     * @version <1.0>  2019-09-06T17:50:02+0800
     */
    public function receiptToPrintQuery($data)
    {
        return $this->httpRequest->apiPost('400017', $data);
    }
}