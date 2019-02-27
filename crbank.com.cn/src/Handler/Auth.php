<?php

/**
 * 提现之前的鉴权处理
 */
namespace jzweb\sat\crbank\Handler;
class Auth extends BankRequest{

    const AUTHMCHURL ="/pay/auth/authentication";
    /**
     * 构造函数
     *
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }


    /**
     * 开始进行体现鉴权
     * @param array $data
     * @return string
     */
    public function startAuth($data)
    {
        return $this->httpRequest->post(self::AUTHMCHURL, $data);
    }

}