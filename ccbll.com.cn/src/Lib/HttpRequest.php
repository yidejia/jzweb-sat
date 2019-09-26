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
        $data = json_encode($data);
        //为了与mcrypt保持一致，加密前用0填充
        // if (strlen($data) % 8) {
        //     $data = str_pad($data,strlen($data) + 8 - strlen($data) % 8, "\0");
        // }

        //对称加密
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('des-ede3'));
        return base64_encode(openssl_encrypt($data, 'des-ede3', $salt, OPENSSL_RAW_DATA, $iv));
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
        $sign = 'INFO=' . $info . '&BODY=' . $body;

        /** 证书私钥加密 */
        $sign = (new Rsa())->rsaP12Sign($sign, $this->config['private_key_path'], $this->config['private_key_keyword_path']);

        /** 签名 => base64 */
        return base64_encode(json_encode([
            'signedMsg' => $sign,
        ]));
    }

    /**
     * [getMessage 报文]
     * @version <1.0>  2019-09-07T17:58:49+0800
     * @param   [type] $trxCode                 [description]
     * @param   [type] $data                    [description]
     * @return  [type]                          [description]
     */
    public function getMessage($trxCode, $data)
    {
        $salt = $this->getNonceStr();
        $info = $this->getInfoMessage($trxCode, $data, $salt);
        // \Log::channel('console')->debug(base64_decode($info));
        //去掉交易流水号
        unset($data['tradeNo']);
        $body = $this->getBodyMessage($data, $salt);
        $sign = $this->getSignMessage(base64_decode($info), json_encode($data));

        return [
            'INFO' => $info,
            'BODY' => $body,
            'SIGN' => $sign,
            'CONTENTTYPE' => 'json',
        ];
    }

    /**
     * [parsingMessage 解析报文]
     * @version <1.0>  2019-09-04T15:27:51+0800
     * @param   [type] $message                 [description]
     * @return  [type]                          [description]
     */
    public function parsingMessage($message, $asynchro=false)
    {
        if (!isset($message['INFO'])) {
            return $message;
        }

        $info = json_decode(iconv('GB2312', 'UTF-8', base64_decode($message['INFO'])), true);
        if (!$info) {
            $info = json_decode(iconv('GB2312', 'UTF-8', base64_decode(urldecode($message['INFO']))), true);
        }
        $sign = json_decode(iconv('GB2312', 'UTF-8', base64_decode($message['SIGN'])), true);
        if (!$sign) {
            $sign = json_decode(iconv('GB2312', 'UTF-8', base64_decode(urldecode($message['SIGN']))), true);
        }
        $signedMsg = $sign['signedMsg'];

        if ($info['salt'] && !$asynchro) {
            /** 证书私钥解密 */
            $salt = (new Rsa())->rsaP12Decrypt($info['salt'], $this->config['private_key_path'], $this->config['private_key_keyword_path']);
            $body = base64_decode($message['BODY']);
            /** des-ede3解密 */
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('des-ede3'));
            $body = json_decode(iconv('GB2312', 'UTF-8', openssl_decrypt($body, 'des-ede3', $salt, OPENSSL_RAW_DATA, $iv)), true);
        } else {
            $body = json_decode(iconv('GB2312', 'UTF-8', base64_decode($message['BODY'])), true);
            if (!$body) {
                $body = json_decode(iconv('GB2312', 'UTF-8', base64_decode(urldecode($message['BODY']))), true);
            }
        }

        /** 验签 */
        $data = 'INFO=' . json_encode($info, JSON_UNESCAPED_UNICODE) . '&BODY=' . json_encode($body, JSON_UNESCAPED_UNICODE);
        $result = (new Rsa())->rsaVerify($data, $signedMsg, $this->config['public_key_path']);

        if (!$result) {
            //throw new ServerException("验签失败");
        }
        return ['info' => $info, 'body' => $body];
    }

    /**
     * [convertMessage 转换报文]
     * @version <1.0>   2019-09-05T15:50:29+0800
     * @param   [type]  $message                 [description]
     * @return  [type]                           [description]
     */
    public function convertMessage($message)
    {
        if (is_array($message)) {
            $result = '';
            foreach ($message as $k => $v){
                $result .= ($result ? '&' : '') . $k . '=' . $v;
            }
        } else {
            $imessage = iconv('GB2312', 'UTF-8', $message);
            if (strstr($imessage, 'returnCode')) {
                $message = explode('&', $imessage);
                $result = [
                    'returnCode' => str_replace('returnCode=', '', $message[0]),
                    'returnMessage' => str_replace('returnMessage=', '', $message[1]),
                ];
            } else {
                $message = explode('&', $message);
                $result = [
                    'INFO' => str_replace('INFO=', '', $message[0]),
                    'BODY' => str_replace('BODY=', '', $message[1]),
                    'SIGN' => str_replace('SIGN=', '', $message[2]),
                ];
            }
        }

        return $result;
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
        $message = $this->getMessage($trxCode, $data);
        // \Log::channel('console')->debug($message);
        $echo = "<form style='display:none;' id='form1' name='form1' method='post' action='" . $this->config['h5_url'] . $this->config['url_query'] . "'>";
        foreach ($message as $k => $v) {
            $echo .= "<input name='{$k}' type='text' value='{$v}' />";
        }
        $echo .= "</form>";
        $echo .= "<script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";

        echo $echo;
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
            //报文
            $message = $this->getMessage($trxCode, $data);
            $message['BODY'] = urlencode($message['BODY']);
            $message = $this->convertMessage($message);
            // \Log::channel('console')->debug($message);
            $res = $client->request('POST', $this->config['url_query'], [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'charset' => 'GBK',
                ],
                'body' => $message,
            ]);
            if ($res->getStatusCode() == 200) {
                $content = $res->getBody()->getContents();
            } else {
                throw  new ServerException("网络请求异常");
            }
            return $this->parsingMessage($this->convertMessage($content));
        } catch (\Exception $e) {
            return ['return_code' => "FAIL", 'return_msg' => $e->getMessage()];
        }
    }
}