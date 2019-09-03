<?php

namespace jzweb\sat\ccbll\Lib;

/**
 * Rsa相关类库
 *
 * @author changge(1282350001@qq.com)
 */
class Rsa
{

    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $public_key_path 公钥文件路径
     * return 签名结果
     */
    public function rsaSign($data, $public_key_path)
    {
        $pubKey = file_get_contents($public_key_path);
        $pubKey = openssl_get_publickey($pubKey);
        $res = openssl_public_encrypt($data, $sign, $pubKey);
        if (!$res) {
            return false;
        }

        openssl_free_key($pubKey);
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * [rsaP12Sign RSA证书+密码签名]
     * @version <1.0>  2019-09-03T11:37:40+0800
     * @param   [type] $data                     [待签名数据]
     * @param   [type] $private_key_path         [证书路径]
     * @param   [type] $private_key_keyword_path [密码路径]
     * @return  [type]                           [签名结果]
     */
    public function rsaP12Sign($data, $private_key_path, $private_key_keyword_path)
    {
        $priKey = file_get_contents($private_key_path);
        $priKeyWd = file_get_contents($private_key_keyword_path);
        //读取证书
        openssl_pkcs12_read($priKey, $certs, $priKeyWd);
        //签名
        openssl_sign($data, $sign, openssl_get_privatekey($certs['pkey']));

        return base64_encode($sign);
    }

    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $ali_public_key_path 支付宝的公钥文件路径
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    public function rsaVerify($data, $ali_public_key_path, $sign)
    {
        $pubKey = file_get_contents($ali_public_key_path);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }

    /**
     * RSA解密
     * @param $content 需要解密的内容，密文
     * @param $private_key_path 商户私钥文件路径
     * return 解密后内容，明文
     */
    public function rsaDecrypt($content, $private_key_path)
    {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        //用base64将内容还原成二进制
        $content = base64_decode($content);
        //把需要解密的内容，按128位拆开解密
        $result = '';
        for ($i = 0; $i < strlen($content) / 128; $i++) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }
}