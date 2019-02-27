<?php

namespace jzweb\sat\jppay\Handler;


use jzweb\sat\jppay\Lib\HttpRequest;

/**
 * 业务处理基类
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/7
 */
abstract class PayRequest
{
    protected $config;
    protected $httpRequest;

    /**
     * 构造函数
     *
     * Business constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->httpRequest = new HttpRequest($config);
    }


}