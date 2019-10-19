<?php

namespace jzweb\sat\ccbpay\Handler;

use jzweb\sat\ccbpay\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 电子登记薄管理类
 * 移动H5,PC端接口用H5地址，API接口用api地址
 * @author changge(1282350001@qq.com)
 */
class merchant extends BaseRequest
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
     * [fileUpload 文件上传]
     * @version <1.0>  2019-09-02T11:20:41+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function fileUpload($data)
    {
        return $this->httpRequest->apiPost('200014', $data);
    }

    /**
     * [personalAccount 个人用户开电子登记簿]
     * @version <1.0>  2019-09-06T11:16:12+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5                      [description]
     * @return  [type] [description]
     */
    public function personalAccount($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('100003', $data) : $this->httpRequest->h5Post('110003', $data);
    }

    /**
     * [merchantAccount 企业用户开电子登记簿]
     * @version <1.0>   2019-09-06T11:21:00+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5                      [description]
     * @return  [type]                           [description]
     */
    public function merchantAccount($data, $h5= false)
    {
        return $h5 ? $this->httpRequest->h5Post('100004', $data) : $this->httpRequest->h5Post('110004', $data);
    }

    /**
     * [personalInfoChange 个人用户信息变更]
     * @version <1.0>   2019-09-06T11:24:56+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5                      [description]
     * @return  [type]                           [description]
     */
    public function personalInfoChange($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('100006', $data) : $this->httpRequest->h5Post('110006', $data);
    }

    /**
     * [personalInfoChange 企业用户信息变更]
     * @version <1.0>   2019-09-06T11:24:56+0800
     * @param   [type]  $data                    [description]
     * @param   boolean $h5                      [description]
     * @return  [type]                           [description]
     */
    public function merchantInfoChange($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('100007', $data) : $this->httpRequest->h5Post('110007', $data);
    }

    /**
     * [accountStatusChange 用户电子登记簿状态变更]
     * @version <1.0>  2019-09-06T11:29:42+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function accountStatusChange($data)
    {
        return $this->httpRequest->apiPost('100008', $data);
    }

    /**
     * [passwordSetting 交易密码安全设置]
     * @version <1.0>  2019-09-06T11:40:00+0800
     * @return  [type] [description]
     */
    public function passwordSetting($data, $h5=false)
    {
        return $h5 ? $this->httpRequest->h5Post('100009', $data) : $this->httpRequest->h5Post('110009', $data);
    }
}