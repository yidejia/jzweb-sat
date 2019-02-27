<?php

namespace jzweb\sat\jppay\Lib;

use GuzzleHttp\Client;
use jzweb\sat\jppay\Exception\ServerException;

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
     * 产生随机字符串，不长于32位
     *
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 按照参数名ASCII 字典序排序如下
     *
     * @param array $params
     * @return string
     */
    public function sortParams($params)
    {
        ksort($params);
        reset($params);
        return urldecode(http_build_query($params));
    }

    /**
     * 构造签名
     *
     * @param array $params
     * @return string
     */
    public function buildSign($params)
    {
        $string = $this->sortParams($params) . "&key=" . $this->config['key'];
        return strtoupper(\md5($string));
    }

    /**
     * 输出xml字符
     *
     * @param array $params
     * @return string
     */
    public function toXml($params)
    {
        if (!is_array($params) || count($params) <= 0) {
            throw  new ServerException("参数不能为空或格式不正确");
        }
        $xml = "<xml>";
        foreach ($params as $key => $val) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     *
     * @param string $xml
     * @return []
     */
    public function fromXml($xml)
    {
        if (!$xml) {
            throw  new ServerException("参数不能为空");
        } else {
            //将XML转为array
            return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', \LIBXML_NOCDATA)), true);
        }
    }


    /**
     * 构造请求数据
     *
     * @param string $api
     * @param array $params
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($api, $params)
    {
        try {
            //商户id
            $params['mch_id'] = $this->config['mch_id'];
            //通知地址
            $params['notify_url'] = $this->config['notify_url'];
            //获取随机字符串
            $params['nonce_str'] = $this->getNonceStr();
            //构造签名
            $params['sign'] = $this->buildSign($params);
            //构造请求xml数据
            $xmlData = $this->toXml($params);
            $res = $this->client->request('POST', $api, ['body' => $xmlData]);
            if ($res->getStatusCode() == 200) {
                $content = $res->getBody();
            } else {
                throw  new ServerException("网络请求异常");
            }
            return $this->fromXml($content);
        } catch (\Exception $e) {
            return ['return_code' => "FAIL", 'return_msg' => $e->getMessage()];
        }
    }
}