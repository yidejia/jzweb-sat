<?php

namespace jzweb\sat\ccbpay\Handler;

use jzweb\sat\ccbpay\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 对账类
 * 移动H5,PC端接口用H5地址，API接口用api地址
 * @author changge(1282350001@qq.com)
 */
class Check extends BaseRequest
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
     * [checkFileToDownload 对账文件下载]
     * 根据清算日期、业务类型下载对账文件。
     * @version <1.0>  2019-09-06T15:57:14+0800
     */
    public function checkFileToDownload($data)
    {
        return $this->httpRequest->apiPost('600001', $data);
    }

    /**
     * [checkFileToDownloadPC 对账文件下载PC]
     * 用户在平台使用PC端发起对账文件下载请求，跳转至存管系统页面，查询对账文件并且选择下载对账文件。
     * @version <1.0>  2019-09-06T15:57:14+0800
     */
    public function checkFileToDownloadPC($data)
    {
        return $this->httpRequest->h5Post('600002', $data);
    }
}