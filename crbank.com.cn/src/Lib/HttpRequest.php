<?php

namespace jzweb\sat\crbank\Lib;

use GuzzleHttp\Client;
use jzweb\sat\crbank\Exception\ServerException;

/**
 * 封装http请求接口
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/6
 */
class  HttpRequest
{

    private $config;
    private $client;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client(['base_uri' => $config['url'], 'timeout' => 30]);
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    private function buildRequestMysign($para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串,不能用 http_build_query ,对方系统对中文不处理
        $prestr = "";
        foreach ($para_sort as $k=>$v) {
            $prestr = $prestr.$k."=".$v."&";
        }
        $prestr = trim($prestr,'&');

        return (new Rsa())->rsaSign($prestr, $this->config['private_key_path']);
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    private function buildRequestPara($para)
    {
        $para['sign_type'] = "RSA";
        $para['plat_no'] = $this->config['fw'];
        $para['nonce_str'] = rand(1000, 9999);
        //排序
        ksort($para);
        reset($para);
        //生成签名结果
        $para['sign'] = $this->buildRequestMysign($para);
        return $para;
    }

    /**
     * 封装http get 方法
     *
     * @param $url
     * @param $data
     * @return string
     */
    public function get($url, $data)
    {
        try {
            $res = $this->client->get(sprintf("%s?%s", $url, http_build_query($this->buildRequestPara($data))));
            if ($res->getStatusCode() == 200) {
                return json_decode($res->getBody(), true);
            } else {
                throw  new ServerException("网络请求异常");
            }
        } catch (\Exception $e) {
            return ['return_code' => "FAIL", 'return_msg' => $e->getMessage()];
        }
    }


    /**
     * 封装http post方法
     *
     * @param $url
     * @param $data
     * @return string
     */
    public function post($url, $data)
    {
        try {
            $url = sprintf("%s", $url);
            $res = $this->client->request('POST', sprintf("%s", $url), [
                'json' => $this->buildRequestPara($data)
            ]);
            if ($res->getStatusCode() == 200) {
                return json_decode($res->getBody(), true);
            } else {
                throw  new ServerException("网络请求异常");
            }
        } catch (\Exception $e) {
            return ['return_code' => "FAIL", 'return_msg' => $e->getMessage()];
        }
    }
}