<?php

namespace jzweb\sat\ccbll\Lib;

use GuzzleHttp\Client;
use jzweb\sat\ccbll\Exception\ServerException;

/**
 * 封装http请求接口
 *
 * @author changge(1282350001@qq.com)
 */
class  HttpRequest
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * [getNonceStr 产生随机字符串，不长于64位]
     * @version <1.0>   2019-09-02T17:36:36+0800
     * @param   integer $length                  [description]
     * @return  [type]                           [description]
     */
    public function getNonceStr($length = 64)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
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
     * [infoMessage INFO报文]
     * @version <1.0>  2019-09-02T17:18:30+0800
     * @param   [type] $trxCode                 [description]
     * @return  [type]                          [description]
     */
    public function getInfoMessage($trxCode, $data, $salt)
    {
        return base64_encode(json_encode([
            'trxCode' => $trxCode, //交易代码
            'version' => $this->config['version'],
            'charSet' => $this->config['charSet'],
            'signType' => $this->config['signType'],
            'dataType' => $this->config['dataType'],
            'PID' => $this->config['PID'],
            'reqSn' => $data['tradeNo'], //交易流水号
            'trxTime' => date('YmdHis'),
            'salt' => (new Rsa())->rsaSign($salt, $this->config['public_key_path']),
        ]));
    }

    /**
     * [getBodyMessage BODY报文]
     * @version <1.0>  2019-09-03T10:21:05+0800
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function getBodyMessage($data, $salt)
    {
        unset($data['tradeNo']);
        return base64_encode(openssl_encrypt(json_encode($data), 'des-ede3', $salt));
    }

    /**
     * [getSignMessage SIGN报文]
     * @version <1.0>  2019-09-03T11:29:41+0800
     * @param   [type] $info                    [description]
     * @param   [type] $body                    [description]
     * @return  [type]                          [description]
     */
    public function getSignMessage($info, $body)
    {
        //原文
        $sign = http_build_query([
            'INFO' => $info,
            'BODY' => $body,
        ]);

        /** 证书私钥加密 */
        $sign = (new Rsa())->rsaP12Sign($sign, $this->config['private_key_path'], $this->config['private_key_keyword_path']);

        return base64_encode(json_encode([
            'signedMsg' => $sign,
        ]));
    }

    /** [apiPost api请求] */
    public function apiPost($trxCode, $data)
    {
        $client = new Client(['base_uri' => $this->config['api_url'], 'timeout' => 30]);
        return $this->post($trxCode, $data, $client);
    }

    /** [h5Post h5请求] */
    public function h5Post($trxCode, $data)
    {
        $client = new Client(['base_uri' => $this->config['h5_url'], 'timeout' => 30]);
        return $this->post($trxCode, $data, $client);
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
    public function post($trxCode, $data, $client)
    {
        try {
            $salt = $this->getNonceStr();
            $info = $this->getInfoMessage($trxCode, $data, $salt);
            $body = $this->getBodyMessage($data, $salt);
            $sign = $this->getSignMessage($info, $body);
            //报文
            $message = [
                'INFO' => $info,
                'BODY' => $body,
                'SIGN' => $sign,
                'CONTENTTYPE' => 'json',
            ];
            //构造请求xml数据
            $xmlData = $this->toXml($message);
            $res = $client->request('POST', $api, [
                'headers' => [
                    'Content-Type' => 'application/xml',
                    'charset' => 'GBK',
                ],
                'body' => $xmlData,
            ]);
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

    /**
     * 请求文件流
     * @author changge(changge1519@gmail.com)
     * @version <1.0>  2020-12-04T17:11:59+0800
     * @return  mixd
     */
    public function apiStream($trxCode, $data)
    {
        $client = new Client(['base_uri' => $this->config['api_url'], 'timeout' => 30]);
        try {
            //报文
            $message = $this->getMessage($trxCode, $data);
            $message['BODY'] = urlencode($message['BODY']);
            $message = $this->formatMessage($message);
            $res = $client->request('POST', $this->config['url_query'], [
                'stream' => true,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'charset' => 'GBK',
                ],
                'body' => $message,
            ]);

            if ($res->getStatusCode() == 200) {
                $body = $res->getBody();
                $content = '';
                while (!$body->eof()) {
                    $content .= $body->read(1024);
                }

                return $this->convertMessage($content);
            } else {
                throw  new ServerException("网络请求异常");
            }
        } catch (\Exception $e) {
            return ['return_code' => "FAIL", 'return_msg' => $e->getMessage()];
        }
    }
}