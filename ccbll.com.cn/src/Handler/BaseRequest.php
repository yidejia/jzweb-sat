<?php

namespace jzweb\sat\ccbll\Handler;


use jzweb\sat\ccbll\Lib\HttpRequest;

/**
 * 业务处理基类
 *
 * @author changge(1282350001@qq.com)
 */
abstract class BaseRequest
{
    protected $config;
    protected $httpRequest;

    /**
     * [__construct 构造函数]
     * @version <1.0>  2019-09-02T11:02:58+0800
     * @param   [type] $config                  [description]
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->httpRequest = new HttpRequest($config);
    }
}