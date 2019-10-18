<?php

namespace jzweb\sat\ccbpay\Handler;

use jzweb\sat\ccbpay\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 通知类
 * 移动H5,PC端接口用H5地址，API接口用api地址
 * @author changge(1282350001@qq.com)
 */
class Notice extends BaseRequest
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
     * [payResNotice 匿名支付结果通知]
     * 通过微信、支付宝、银联等匿名支付成功后，龙存管系统主动通知平台支付结果。
     * @version <1.0>  2019-09-06T16:01:29+0800
     */
    public function payResNotice($data)
    {
        return $this->httpRequest->apiPost('200009', $data);
    }

    /**
     * [asynchroNotice 异步通知]
     * @version <1.0>  2019-09-09T15:46:44+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function asynchroNotice($data)
    {
        return $this->httpRequest->parsingMessage($data, true);
    }
}