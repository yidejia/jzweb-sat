<?php

namespace jzweb\sat\ccbpay;

use jzweb\sat\ccbll\Handler\Notice;
use jzweb\sat\ccbll\Handler\Query;
use jzweb\sat\ccbll\Handler\Trade;
use jzweb\sat\jzpay\JzPayInterface;

/**
 * 龙存管官方操作SDK
 *
 * Class client
 * @package jzweb\sat\ccbll
 */
class Client implements JzPayInterface
{

    private $config;

    //支付类型汇总
    const PAYTYPE_C = 'C';  //C:微信APP支付
    const PAYTYPE_D = 'D';  //D:支付宝APP支付
    const PAYTYPE_H = 'H';  //H:一码付（H5二维码）
    const PAYTYPE_I = 'I';  //I:微信扫码支付
    const PAYTYPE_J = 'J';  //J:微信公众号支付
    const PAYTYPE_K = 'K';  //K:建行信用卡分期支付
    const PAYTYPE_L = 'L';  //L:银联在线
    const PAYTYPE_M = 'M';  //M:建行网关对公
    const PAYTYPE_N = 'N';  //N:建行网关对私
    const PAYTYPE_P = 'P';  //P:支付宝扫码
    const PAYTYPE_Q = 'Q';  //Q:一码付（自定义二维码）
    const PAYTYPE_R = 'R';  //R:龙支付扫码
    const PAYTYPE_S = 'S';  //S:龙支付APP支付
    const PAYTYPE_W = 'W';  //W:微信小程序支付


    /**
     * 构造函数
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 转换支付类型
     *
     * @param string $payType
     * @return mixed
     */
    private function changePayType($payType)
    {
        $payTypes = [
            'C' => 'trade.weixin.apppay',    //C:微信APP支付
            'D' => 'trade.alipay.apppay',    //D:支付宝APP支付
            'H' => 'trade.unionpay.native',  //H:一码付（H5二维码）
            'I' => 'trade.weixin.native',    //I:微信扫码支付
            'J' => 'trade.weixin.jspay',     //J:微信公众号支付
            'K' => '',                       //K:建行信用卡分期支付
            'L' => '',                       //L:银联在线
            'M' => '',                       //M:建行网关对公
            'N' => '',                       //N:建行网关对私
            'P' => 'trade.alipay.native',    //P:支付宝扫码
            'Q' => '',                       //Q:一码付（自定义二维码）
            'R' => '',                       //R:龙支付扫码
            'S' => '',                       //S:龙支付APP支付
            'W' => 'trade.weixin.mppay',     //W:微信小程序支付
        ];
        //返回转换后的类型
        if (isset($payTypes[strtoupper($payType)])) {
            return $payTypes[strtoupper($payType)];
        } else {
            return "";
        }
    }

    /**
     * 构造请求参数
     *
     * @param string $payType 支付渠道
     * @param string $data 支付数据
     * @param string $trxType 业务类型包括：12001:B2C商城消费、12002:B2C商城消费合伙人模式、12006:B2B商城消费、12007:B2B商城消费合伙人模式
     *
     * @return array
     */
    private function buildRequestParams($payType, $data, $trxType = "12001")
    {
        list($merc_no, $goods_str, $goods_ids_str, $quantity, $customer_mobile) = explode(";", $data['body']);

        $params = [
            'tradeNo' => $data['trade_no'],
            'payType' => $payType,
            'mercOrdNo' => $data['out_trade_no'],
            'trxType' => $trxType,
            'trAmt' => $data['total_fee'],
            'tradt' => date('Ymd'), //交易日期
            'tratm' => date('His'), //交易时间
            'pageRetUrl' => $data['return_url'], //页面返回url
            'bgRetUrl' => $this->config['callback_pay_url'],   //后台通知url
            'ccy' => 'CNY',
            'platFeeAmt' => round($data['total_fee'] * 0.1),
            'prdSumAmt' => $data['total_fee'],
            'cnt' => 1,
            'Lists' => [],
            'ordValTmUnit' => 'H', //订单有效时间单位,D:日、H:时、M:分、S:秒
            'ordValTmCnt' => 6,
            'sumExpressAmt' => 0,
            'sumInsuranceAmt' => 0,
            'cntlist1' => 0,
            'Lists1' => [],
            'rmk2' => $data['appid'] ?: "", //预留字段2，微信小程序/微信公众号支付时必输，上送微信小程序/微信公众号的APPID
            'rmk3' => $data['openid'] ?: "", //预留字段3,微信小程序/微信公众号支付时必输，上送微信小程序/微信公众号的用户子标识OPENID
        ];
        $params['Lists'][] = [
            'tradeOrdNo' => $data['out_trade_no'],
            'mercMbrCode' => $merc_no, //填写子商户信息
            'tradeNm' => $goods_str,    //填产品商品串
            'tradeRmk' => $goods_ids_str,   //填产品ID
            'tradeNum' => $quantity,   //填写产品总数量
            'tradeAmt' => $data['total_fee'],
            'platFeeAmt1' => round($data['total_fee'] * 0.1),
            'cMbl' => $customer_mobile,       //不填无法进行确认收货
        ];
        return $params;
    }


    /**
     * 微信公众号支付
     *
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $appid
     * @param string $openid
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     *
     * @return array|mixed|void
     */
    public function weixinJsPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.weixin.native
     * 微信扫码支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixiNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_J, func_get_args());
        return (new Trade($this->config))->anonyPay($data);
    }

    /**
     * trade.weixin.apppay
     * 微信APP支付，调用统一下单接口【拉起微信APP支付,微信官方原生的】
     * todo 我们目前的产品,暂时没有开通该服务
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.weixin.apppay2
     * 微信APP+支付，调用统一下单接口【拉起优洛微信小程序支付】
     * 我们目前的产品，开通了该服务
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinAppPay2($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        return [
            'result_code' => "SUCCESS",
            'return_code' => "SUCCESS",
            'package_json' => json_encode(
                [
                    'pay_type' => "ccbpay",
                    "original_id" => $this->config['original_id'],
                    "app_id" => $this->config['app_id'],
                    "prepay_id" => md5(json_encode(func_get_args))
                ]
            )
        ];
    }

    /**
     * trade.weixin.h5pay
     * 微信h5支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinH5Pay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.weixin.mppay
     * 适用微信小程序中拉起微信支付。
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $appid
     * @param string $openid
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weixinMpPay($trade_no, $appid, $openid, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {

        $params = [
            'trade_no' => $trade_no,
            'appid' => $appid,
            'openid' => $openid,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee,
            'body' => $body,
            'ip' => $ip,
            'return_url' => $return_url
        ];
        $data = $this->buildRequestParams(self::PAYTYPE_W, $params);
        $result = (new Trade($this->config))->anonyPay($data);
        if ($result && $result['body']['rstCode'] == "0") {
            $url = $result['body']['mercOrdMsg'];
            if ($content = file_get_contents($url)) {
                $package = json_decode($content, true);
                if ($package['SUCCESS']) {
                    return [
                        'mch_id' => $package['partnerid'],
                        'package_json' => json_encode(
                            [
                                'timeStamp' => $package['timeStamp'],
                                'nonceStr' => $package['nonceStr'],
                                'package' => $package['package'],
                                'signType' => $package['signType'],
                                'paySign' => $package['paySign']
                            ]
                        ),
                        'prepay_id' => substr($package['package'], 9),
                        'result_code' => "SUCCESS",
                        'return_code' => "SUCCESS",
                        'sign' => md5($result['info']['salt']),
                        'trade_type' => $this->changePayType(self::PAYTYPE_W),
                    ];
                } else {
                    return ['err_code' => 888889, "err_code_des" => "获取签名数据失败2"];
                }
            } else {
                return ['err_code' => 888890, "err_code_des" => "获取签名数据失败1"];
            }
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * trade.weixin.micropay
     * 微信刷卡支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function weixinMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.native
     * 支付宝扫码支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        $data = $this->buildRequestParams(self::PAYTYPE_P, func_get_args());
        return (new Trade($this->config))->anonyPay($data);
    }

    /**
     * trade.alipay.jspay
     * 支付宝公众号支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function alipayJsPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.h5pay
     * 支付宝H5支付，调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alipayH5Pay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.alipay.micropay
     * 支付宝小额支付，刷卡支付有单独的支付接口，不调用统一下单接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function alipayMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.unionpay.native
     * 银联扫码支付，调用统一下单接。
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $out_trade_no
     * @param $total_fee
     * @param string $body
     * @param string $ip
     * @param string $return_url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function unionpayNative($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }

    /**
     * trade.unionpay.micropay
     * 银联刷卡支付，调用统一下单接。
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $body
     * @param string $ip
     * @return array
     */
    public function unionpayMicroPay($trade_no, $out_trade_no, $total_fee, $body, $ip = "127.0.0.1", $return_url = "")
    {
        //todo 暂时不支持该支付方式
        return ['error_code' => 888888, 'err_code_dsc' => '系统暂时不支持该支付方式'];
    }


    /**
     * 回调通知验签
     * 商户系统对于支付结果通知的内容一定要做签名验证
     * 防止数据泄漏导致出现“假通知”，造成资 金损失
     *
     * @param string $xml
     * @return array|bool
     */
    public function verifySignCallBack($xml)
    {
        return (new Notice($this->config))->asynchroNotice($xml);
    }

    /**
     * 订单查询接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderQuery($trade_no, $out_trade_no)
    {
        $data = [
            'tradeNo' => $trade_no,
            'mercOrdNo' => $out_trade_no, //订单单号
            'trxType' => '12001', //12001:B2C商城消费、12002:B2C商城消费合伙人模式、12005:用户缴费、12006:B2B商城消费、12007:B2B商城消费合伙人模式、12008:商品退款、19000:个人账户入金、19001:个人账户出金、19002:企业账户出金、19003:平台账户入金、19004:平台账户出金、21004:佣金分润、22007:平台缴费、22008:其他费用缴纳
        ];
        $result = (new Query($this->config))->tradeQuery($data);
        if ($result && $result['body']['rstCode'] == "0") {
            if ($result['body']['traSts'] == '0') {
                return [
                    'bank_no' => "",
                    'bank_type' => "",
                    'cash_fee' => $result['body']['actTramt'],
                    'fee_type' => $result['body']['ccy'],
                    'out_trade_no' => $out_trade_no,
                    'result_code' => "SUCCESS",
                    'return_code' => "SUCCESS",
                    'sign' => $result['info']['salt'],
                    'sub_openid' => "",
                    'third_trans_id' => "",
                    'time_end' => $result['body']['tradt'] . $result['body']['tratm'],
                    'total_fee' => $result['body']['otratm'],
                    'trade_state' => "SUCCESS",
                    'trade_type' => '',
                    'transaction_id' => $this->config['PID'] . $result['body']['jrnno'],
                ];
            } else {
                return [
                    "mch_id" => $this->config['PID'],
                    "result_code" => "SUCCESS",
                    "return_code" => "SUCCESS",
                    "sign" => $result['info']['salt'],
                    "trade_state" => "NOTPAY",
                    "trade_type" => ""
                ];
            }
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 订单退款接口
     * 注意：如果不填手续费，那么手续费将由商户承担
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     * @param string $out_refund_no
     * @param int $total_fee
     * @param int $refund_fee
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefund($trade_no, $out_trade_no, $out_refund_no, $total_fee, $refund_fee, $body = "伊的家商城订单")
    {
        list($goods_str, $goods_ids_str, $remark) = explode(";", $body);
        $data = [
            'tradeNo' => $trade_no,
            'refundOrdNo' => $out_refund_no,
            'trxType' => '12008', //12008:商品退款，12014:佣金退款
            'operType' => $total_fee == $refund_fee ? '21' : '22', //针对子订单，子订单退全额就是全额退款
            'oriOrdNo' => $out_trade_no,
            'oriOrdAmt' => $refund_fee,
            'refundDt' => date('Ymd'),
            'refundTm' => date('His'),
            'tradeOrdNo' => $out_trade_no,
            'tradeNm' => $goods_str,
            'tradeRmk' => $goods_ids_str,
            'tradeNum' => 1,
            'tradeAmt' => $refund_fee,
            'feeAmt' => 0, //从商户余额账户扣除一笔手续费到平台账户，应保持为0
            'platFeeAmt1' => round($refund_fee * 0.1), //填了手续费由平台承担，不填由商户承担
            'remark' => $remark,
        ];
        $result = (new Trade($this->config))->refund($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'mch_id' => $this->config['PID'],
                'out_refund_no' => $out_refund_no,
                'out_trade_no' => $out_trade_no,
                'refund_channel' => 'ORIGINAL',
                'refund_fee' => $refund_fee,
                'refund_id' => $result['body']['jrnno'],
                'result_code' => 'SUCCESS',
                'return_code' => 'SUCCESS',
                'sign' => $result['info']['salt'],
                'third_trans_id' => '',
                'total_fee' => $total_fee,
                'transaction_id' => $this->config['PID'] . $result['body']['jrnno'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

    /**
     * 订单退款进度查询接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_trade_no
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderRefundQuery($trade_no, $out_trade_no, $out_refund_no)
    {
        $data = [
            'tradeNo' => $trade_no,
            'mercOrdNo' => $out_trade_no, //交易订单单号
            'mercRfOrdNo' => $out_refund_no, //退款订单单号
            'trxType' => '12008', //12001:B2C商城消费、12002:B2C商城消费合伙人模式、12005:用户缴费、12006:B2B商城消费、12007:B2B商城消费合伙人模式、12008:商品退款、19000:个人账户入金、19001:个人账户出金、19002:企业账户出金、19003:平台账户入金、19004:平台账户出金、21004:佣金分润、22007:平台缴费、22008:其他费用缴纳
        ];
        $result = (new Query($this->config))->tradeQuery($data);
        if ($result && $result['body']['rstCode'] == "0") {
            return [
                'cash_fee' => $result['body']['actTramt'],
                'fee_type' => $result['body']['ccy'],
                'mch_id' => $this->config['PID'],
                'out_refund_no_0' => $out_refund_no,
                'out_trade_no' => $out_trade_no,
                'refund_channel_0' => "ORIGINAL",
                'refund_count' => $result['body']['retNum'],
                'refund_fee_0' => $result['body']['refundAmt'],
                'refund_id_0' => $result['body']['jrnno'],
                'refund_status_0' => $result['body']['traSts'] == "0" ? "SUCCESS" : ("PROCESSING" . $result['body']['traSts']),
                'refund_success_time_0' => $result['body']['tradt'] . $result['body']['tratm'],
                'result_code' => "SUCCESS",
                'return_code' => "SUCCESS",
                'sign' => $result['info']['salt'],
                'third_trans_id' => "",
                'total_fee' => $result['body']['otratm'],
                'transaction_id' => $this->config['PID'] . $result['body']['jrnno'],
            ];
        } else {
            return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg']];
        }
    }

}