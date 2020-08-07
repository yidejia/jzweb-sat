<?php

namespace jzweb\sat\ccbll\Lib;

/**
 * 辅助函数
 *
 * @author changge(1282350001@qq.com)
 * @version <1.0>  2020-08-07T20:33:39+0800
 */
class Helpers
{
    private $config;

    /**
     * 构造
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 生成签名
     *
     * @param   array   $data     签名数据
     * @return  string
     */
    public function makeSign($data)
    {
        //按字典序排序参数
        ksort($data);
        $string = $this->ToUrlParams($data);
        //加入KEY
        $string = $string . "&key=" . $this->config['api_key'];
        //MD5加密
        $string = md5($string);
        //所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     *
     * @param   array   $data     数据
     * @return  string
     */
    public function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 获取指定长度的随机字母数字组合
     *
     * @param $length
     * @param string $string
     * @return string
     */
    function random($length, $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz')
    {
        $random = '';
        $strlen = strlen($string);

        for ($i = 0; $i < $length; ++$i) {
            $random .= $string[mt_rand(0, $strlen - 1)];
        }

        return $random;
    }
}