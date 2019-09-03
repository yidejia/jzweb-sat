<?php

namespace jzweb\sat\ccbll\Handler;

use jzweb\sat\ccbll\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 电子登记薄管理类
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

}