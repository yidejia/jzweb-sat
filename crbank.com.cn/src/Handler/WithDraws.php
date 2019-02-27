<?php

/**
 * 分账宝 批量提现文件 上传
 */
namespace jzweb\sat\crbank\Handler;


class  WithDraws  extends BankRequest {

    /**
     * 构造函数
     * @param $config
     */
    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * 批量提现上传
     *
     * @param string $file 要上传的文件名称
     * @return string
     */
    public function batWithDraws($file) {
        return $this->sftpRequest->upload($file,true);
    }

    /**
     * 获取批量操作结果
     *
     * @param string $dir
     * @param string $file
     * @return string
     */
    public function getWithDrawsResp($dir, $file) {
        return $this->sftpRequest->resp($dir, $file);
    }
}
