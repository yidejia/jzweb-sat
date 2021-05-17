<?php

namespace jzweb\sat\ccbpay\Lib;

use GuzzleHttp\Client;
use jzweb\sat\ccbpay\Exception\ServerException;
use jzweb\sat\ccbpay\Lib\Log;
use YiDeJia\Zipkin\Native\HttpClientFactory;

/**
 * 封装http请求接口
 *
 * @author changge(1282350001@qq.com)
 */
class HttpRequest
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->log = new Log($config);
    }

    /**
     * [getNonceStr 产生随机字符串，不长于64位]
     *
     * @param integer $length [description]
     *
     * @return  [type]                           [description]
     * @version <1.0>   2019-09-02T17:36:36+0800
     */
    private function getNonceStr($length = 64)
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
     *
     * @param   [type] $trxCode                 [description]
     *
     * @return  [type]                          [description]
     * @version <1.0>  2019-09-02T17:18:30+0800
     */
    private function getInfoMessage($trxCode, $tradeNo, $salt, $isBase64Encode = false)
    {

        $result = json_encode([
            'trxCode' => $trxCode, //交易代码
            'version' => $this->config['version'],
            'charSet' => $this->config['charSet'],
            'signType' => $this->config['signType'],
            'dataType' => $this->config['dataType'],
            'PID' => $this->config['PID'],
            'reqSn' => $tradeNo, //交易流水号
            'trxTime' => date('YmdHis'),
            'salt' => (new Rsa())->rsaSign($salt, $this->config['public_key_path']),
        ]);

        return $isBase64Encode ? base64_encode($result) : $result;
    }

    /**
     * [getBodyMessage BODY报文]
     *
     * @param   [type] $data                    [description]
     *
     * @return  [type]                          [description]
     * @version <1.0>  2019-09-03T10:21:05+0800
     */
    private function getBodyMessage($data, $salt, $isBase64Encode = false)
    {
        $data = json_encode($data);
        //为了与mcrypt保持一致，加密前用0填充
        // if (strlen($data) % 8) {
        //     $data = str_pad($data,strlen($data) + 8 - strlen($data) % 8, "\0");
        // }

        //对称加密
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('des-ede3'));
        $result = openssl_encrypt($data, 'des-ede3', $salt, OPENSSL_RAW_DATA, $iv);
        return $isBase64Encode ? base64_encode($result) : $result;
    }

    /**
     * [getSignMessage SIGN报文]
     *
     * @param   [type] $info                    [description]
     * @param   [type] $body                    [description]
     *
     * @return  [type]                          [description]
     * @version <1.0>  2019-09-03T11:29:41+0800
     */
    private function getSignMessage($info, $body, $isBase64Encode = false)
    {
        //原文
        $sign = 'INFO=' . $info . '&BODY=' . $body;

        /** 证书私钥加密 */
        $sign = (new Rsa())->rsaP12Sign($sign, $this->config['private_key_path'], $this->config['private_key_keyword_path']);

        $result = json_encode(['signedMsg' => $sign]);

        /** 签名 => base64 */
        return $isBase64Encode ? base64_encode($result) : $result;
    }

    /**
     * [getMessage 报文]
     *
     * @param   [type] $trxCode                 [description]
     * @param   [type] $data                    [description]
     *
     * @return  [type]                          [description]
     * @version <1.0>  2019-09-07T17:58:49+0800
     */
    private function getMessage($trxCode, $data)
    {
        //获取盐值
        $salt = $this->getNonceStr();
        //获取Info原文
        $info = $this->getInfoMessage($trxCode, $data['tradeNo'], $salt);
        //获取Body报文
        //去掉交易流水号
        unset($data['tradeNo']);
        $body = $this->getBodyMessage($data, $salt, true);
        //获取签名报文
        $sign = $this->getSignMessage($info, json_encode($data), true);

        return [
            'INFO' => base64_encode($info),
            'BODY' => $body,
            'SIGN' => $sign,
            'CONTENTTYPE' => 'json',
        ];
    }

    /**
     * 转换编码
     *
     * @param string $message
     *
     * @return bool|string
     */
    private function convertMessage($message)
    {
        return @iconv('GB2312', 'UTF-8', $message);
    }

    /**
     * [convertMessage 转换报文]
     *
     * @param   [type]  $message                 [description]
     *
     * @return  [type]                           [description]
     * @version <1.0>   2019-09-05T15:50:29+0800
     */
    private function formatMessage($message)
    {
        if (is_array($message)) {
            $result = '';
            foreach ($message as $k => $v) {
                $result .= ($result ? '&' : '') . $k . '=' . $v;
            }
            return $result;
        }

        $output = [];
        parse_str($message, $output);

        if (isset($output['returnCode'])) {
            $result = [
                'returnCode' => $output['returnCode'],
                'returnMessage' => $this->convertMessage($output['returnMessage']),
            ];

            //写日志
            if ($this->config['debug']) {
                $this->log->log('报文解码:' . json_encode($result));
            }

            return $result;
        } else {
            return [
                'INFO' => str_replace(' ', '+', $output['INFO']),
                'BODY' => str_replace(' ', '+', $output['BODY']),
                'SIGN' => str_replace(' ', '+', $output['SIGN']),
            ];
        }
    }

    /**
     * 构造请求数据
     *
     * @param string $api
     * @param array  $params
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function post($trxCode, $data, $client)
    {
        try {
            //报文
            $message = $this->getMessage($trxCode, $data);
            $message['BODY'] = urlencode($message['BODY']);
            $message = $this->formatMessage($message);
            $res = $client->request('POST', $this->config['url_query'], [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'charset' => 'GBK',
                ],
                'body' => $message,
            ]);

            //写日志
            if ($this->config['debug']) {
                $this->log->log('请求的路由地址:' . $this->config['url_query']);
                $this->log->log('打印请求参数串:' . json_encode($data));
                $this->log->log("打印调试信息:" . sprintf("请求流水号:%s API:%s 响应状态码:%d", $data['tradeNo'], $trxCode, $res->getStatusCode()));
            }
            if ($res->getStatusCode() == 200) {
                $content = $this->formatMessage($res->getBody()->getContents());
            } else {
                throw new ServerException("网络请求异常");
            }
            return $this->parsingMessage($content);
        } catch (\Exception $e) {
            if ($this->config['debug']) {
                $this->log->log('请求的路由地址:' . $this->config['url_query']);
                $this->log->log('打印请求参数串:' . json_encode($data));
                $this->log->log("打印调试信息:" . sprintf("请求流水号:%s API:%s", $data['tradeNo'], $trxCode));
                $this->log->log("异常错误信息:" . $e->getMessage(), 'error');
            }
            return ['returnCode' => "FAIL", 'returnMessage' => $e->getMessage()];
        }
    }

    /**
     * 验签操作
     *
     * @param $message
     *
     * @return bool
     */
    private function verifySign($message, $asynchro = false)
    {
        //获取info原文
        if (strpos($message['INFO'], '%') !== false) {
            $info = base64_decode(urldecode($message['INFO']));
        } else {
            $info = base64_decode($message['INFO']);
        }
        $infoMsg = json_decode($this->convertMessage($info), true);

        //获取body原文
        if (isset($infoMsg['salt']) && $infoMsg['salt'] && !$asynchro) {
            //证书私钥解密
            $salt = (new Rsa())->rsaP12Decrypt($infoMsg['salt'], $this->config['private_key_path'], $this->config['private_key_keyword_path']);
            $body = base64_decode($message['BODY']);
            //des-ede3解密
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('des-ede3'));
            $body = openssl_decrypt($body, 'des-ede3', $salt, OPENSSL_RAW_DATA, $iv);
        } else {
            if (strpos($message['BODY'], '%') !== false) {
                $body = base64_decode(urldecode($message['BODY']));
            } else {
                $body = base64_decode($message['BODY']);
            }
        }

        //获取签名
        $sign = base64_decode($asynchro ? urldecode($message['SIGN']) : $message['SIGN']);
        $signedMsg = json_decode($sign, true);
        $signedMsg = $signedMsg['signedMsg'];

        //验签
        $data = 'INFO=' . $info . '&BODY=' . $body;
        return (new Rsa())->rsaVerify($data, $signedMsg, $this->config['public_key_path']);
    }

    /**
     * [parsingMessage 解析报文]
     *
     * @param   [type] $message                 [description]
     *
     * @return  [type]                          [description]
     * @version <1.0>  2019-09-04T15:27:51+0800
     */
    public function parsingMessage($message, $asynchro = false)
    {
        //异步请求日志
        if ($this->config['debug'] && $asynchro) {
            $this->log->log('异步通知解码:' . json_encode($message));
        }

        if (!isset($message['INFO'])) {
            return $message;
        }

        if (strpos($message['INFO'], '%') !== false) {
            $info = json_decode($this->convertMessage(base64_decode(urldecode($message['INFO']))), true);
        } else {
            $info = json_decode($this->convertMessage(base64_decode($message['INFO'])), true);
        }

        //验签操作 TODO
        if (!in_array($info['trxCode'], ['400005', '200007']) && !$this->verifySign($message, $asynchro)) {
            throw new ServerException("验签失败");
        }

        if (isset($info['salt']) && $info['salt'] && !$asynchro) {
            /** 证书私钥解密 */
            $salt = (new Rsa())->rsaP12Decrypt($info['salt'], $this->config['private_key_path'], $this->config['private_key_keyword_path']);
            $body = base64_decode($message['BODY']);
            /** des-ede3解密 */
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('des-ede3'));
            $body = json_decode($this->convertMessage(openssl_decrypt($body, 'des-ede3', $salt, OPENSSL_RAW_DATA, $iv)), true);
        } else {
            if (strpos($message['BODY'], '%') !== false) {
                $body = json_decode($this->convertMessage(base64_decode(urldecode($message['BODY']))), true);
            } else {
                $body = json_decode($this->convertMessage(base64_decode($message['BODY'])), true);
            }
        }

        $result = [
            'info' => $info,
            'body' => $body,
        ];

        //写日志
        if ($this->config['debug']) {
            $this->log->log(($asynchro ? '异步通知' : '请求结果') . '解码:' . json_encode($result));
        }

        return $result;
    }

    /**
     * 构造apiPost请求
     *
     * @param $trxCode
     * @param $data
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function apiPost($trxCode, $data)
    {
        $client = (new HttpClientFactory)->create([
            'base_uri' => $this->config['api_url'],
            'timeout' => 30,
            'tracing_error_throw' => false,
            'response_log' => true
        ]);
//        $client = new Client(['base_uri' => $this->config['api_url'], 'timeout' => 30]);
        return $this->post($trxCode, $data, $client);
    }

    /**
     * 构造H5post请求
     *
     * @param $trxCode
     * @param $data
     */
    public function h5Post($trxCode, $data)
    {
        $message = $this->getMessage($trxCode, $data);
        //写日志
        if ($this->config['debug']) {
            $this->log->log("交易代码:" . $trxCode);
            $this->log->log("请求的路由地址:" . $this->config['h5_url']);
            $this->log->log("打印请求参数串:" . json_encode($data));
        }

        $echo = "<form style='display:none;' id='form1' name='form1' method='post' action='" . $this->config['h5_url'] . $this->config['url_query'] . "'>";
        foreach ($message as $k => $v) {
            $echo .= "<input name='{$k}' type='text' value='{$v}' />";
        }
        $echo .= "</form>";
        $echo .= "<script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";

        echo $echo;
    }
}
