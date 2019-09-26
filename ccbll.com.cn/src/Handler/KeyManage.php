<?php

namespace jzweb\sat\ccbll\Handler;

use jzweb\sat\ccbll\Handler\BaseRequest;

/**
 * 龙存管
 *
 * 密钥管理类
 * 移动H5,PC端接口用H5地址，API接口用api地址
 * @author changge(1282350001@qq.com)
 */
class KeyManage extends BaseRequest
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
     * [platKeyUpload 平台密钥上传]
     * @version <1.0>  2019-09-06T15:50:07+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function platKeyUpload($data)
    {
        return $this->httpRequest->apiPost('200023', $data);
    }
}