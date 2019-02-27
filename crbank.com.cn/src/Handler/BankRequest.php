<?php

namespace jzweb\sat\crbank\Handler;


use jzweb\sat\crbank\Lib\HttpRequest;
use jzweb\sat\crbank\Lib\SFtpRequest;

/**
 * 业务处理基类
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/7
 */
abstract class BankRequest
{
    protected $config;
    protected $httpRequest;
    protected $sftpRequest;

    /**
     * 构造函数
     *
     * Business constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->httpRequest = new HttpRequest($config);
        $this->sftpRequest = new SFtpRequest($config);
    }


}