<?php

/**
 * 分账宝订单&交易 上传
 */

namespace jzweb\sat\crbank\Handler;


class  OrderTrade extends BankRequest {


    /**
     * 构造函数
     * @param $config
     */
    public function __construct($config) {

        parent::__construct($config);
    }



    /**
     * 商户批量订单或交易上传
     *
     * @param string $file 要上传的文件名称
     * @return string
     */
    public function batOrderOrTrade($file) {
        return $this->sftpRequest->upload($file);
    }

    /**
     * 商户批量订单或交易上传 -- 处理过期数据
     *
     * @param string $file 要上传的文件名称
     * @param string $date 日期
     * @return string
     */
    public function batOverOrderOrTrade($file, $date) {
        return $this->sftpRequest->uploadOver($file, $date);
    }

    /**
     * 获取批量操作结果
     *
     * @param string $dir
     * @param string $file
     * @return string
     */
    public function getBatchResp($dir, $file) {
        return $this->sftpRequest->resp($dir, $file);
    }

    /**下载对账单
     * @param $dir
     * @param $file
     * @return string
     */
    public function downLoadBillData($dir, $file) {
        return $this->sftpRequest->resp($dir, $file, true);
    }
}
