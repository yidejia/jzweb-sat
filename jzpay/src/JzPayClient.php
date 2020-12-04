<?php

namespace jzweb\sat\jzpay;


/**
 * 经传聚合支付客户端
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2019/10/18
 */
class JzPayClient
{
    private static $instance = [];

    /**
     * 获取支付单例
     *
     * @param string $driver 支付驱动(jppay|ccbpay)
     * @param array $config 支付配置
     * @return string
     */
    public static function getInstance($driver, $config)
    {
        if ($driver == "jppay") {
            if (!self::$instance[$driver])
                self::$instance[$driver] = new  \jzweb\sat\jppay\Client($config);
        } elseif ($driver == "ccbpay") {
            if (!self::$instance[$driver])
                self::$instance[$driver] = new  \jzweb\sat\ccbpay\Client($config);
        } else {
            if (!self::$instance[$driver])
                self::$instance[$driver] = new  \jzweb\sat\jppay\Client($config);
        }

        return self::$instance[$driver];
    }
}
