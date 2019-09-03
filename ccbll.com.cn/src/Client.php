<?php

namespace jzweb\sat\ccbll;

use jzweb\sat\ccbll\Handler\Merchant;

/**
 * 龙存管操作SDK
 *
 * Class client
 * @package jzweb\sat\ccbll
 */
class Client
{
    private $config;

    /**
     * 构造函数
     *
     * client constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * [fileUpload 文件上传]
     * @version <1.0>  2019-09-02T11:20:41+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function fileUpload($data)
    {
        return (new Merchant($this->config))->fileUpload($data);
    }

}