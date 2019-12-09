<?php

namespace jzweb\sat\ccbll;

/**
 * 龙存管官方操作SDK
 *
 * Class client
 * @package jzweb\sat\ccbll
 */
class WxPayAdapter extends Client
{


    /**
     * 构造函数
     *
     * WxPayAdapter constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }


    /**
     *文件上传
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param url $file_url 软链接，外部可访问
     * @param string $oper_type 操作类型：01:新增，02:修改，默认为01
     * @return array
     */
    public function uploadFile($trade_no, $file_url, $oper_type = "01")
    {
        $data = [
            "tradeNo" => $trade_no,
            "fileUrl" => $file_url,
            "fileType" => "01", //文件格式:01:图片,02:PDF文件
            "operType" => $oper_type,
        ];

        $result = (new Client($this->config))->fileUpload($data);

        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return ['return_code' => "SUCCESS", 'return_msg' => $result['body']['fileId']];
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }

    }


    /**
     * 企业用户开电子登记簿
     * 必须有页面支撑，无法再测试用例里面直接调用
     * 会跳到建行页面输入结算账户名，结算卡号，交易密码，以及打款验证
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param int $entity_id 后台单位ID
     * @param string $entity_name 后台单位名称
     * @param string $legal 公司法人
     * @param string $legal_id_card 公司法人身份证
     * @param string $agent 联系人
     * @param string $agent_id_card 联系人身份证
     * @param int $agent_mobile 联系人的手机号码
     * @param string $buss_pic_id 公司营业执照
     * @param string $legal_front_pic_id 法人身份证正面
     * @param string $legal_back_pic_id 法人身份证反面
     * @param string $cert_pic_id 授权书图片ID
     * @param int $role_id 存管企业角色:100:企业店铺、101：企业买家、002:个体工商户店铺、300:交易市场物流企业、310:交易市场仓储企业
     * @param string $registrant 注册人身份，1:法定代表人、2:授权人
     * @param int $acc_type //账户类型，1:对私,2:对公
     * @param bool $is_mobile_view
     * @param string $return_url
     */
    public function createAccountByWeb($trade_no, $entity_id, $entity_name, $legal, $legal_id_card, $agent, $agent_id_card, $agent_mobile, $buss_pic_id, $legal_front_pic_id, $legal_back_pic_id, $cert_pic_id = "", $role_id = "100", $registrant = 1, $acc_type = 2, $is_mobile_view = false, $return_url = "")
    {
        $data = [
            'tradeNo' => $trade_no,
            'platCusNO' => $entity_id,
            'platRoleID' => $role_id,
            'busFullNm' => $entity_name,
            'registrant' => $registrant,
            'legalPerNm' => $legal,
            'legalPerIdTyp' => '01',
            'legalPerIdNo' => $legal_id_card,
            'agent' => $agent,
            'agentIdType' => '01',
            'agentIdNo' => $agent_id_card,
            'agentMbl' => $agent_mobile,
            'accType' => $acc_type,
            'isAcFlg' => 1,
            'busRating' => '00',
            'pageRetUrl' => $return_url,
            'bgRetUrl' => $this->config['callback_create_account_url'],
            'bussLicenseID' => $buss_pic_id,
            'legalFrontPic' => $legal_front_pic_id,
            'legalBackPic' => $legal_back_pic_id,
            'certPic' => $cert_pic_id,
        ];
        (new Client($this->config))->merchantAccount($data, $is_mobile_view);
    }

    /**
     * [API] 企业用户开电子登记簿
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param int $entity_id 后台单位ID
     * @param int $credit_cd 社会统一信用代码
     * @param string $entity_name 后台单位名称
     * @param string $legal 公司法人
     * @param string $legal_id_card 公司法人身份证
     * @param string $legal_mobile  公司法人手机号
     * @param string $account   结算账户
     * @param string $account_name  结算账户名
     * @param string $bank_name     开户银行
     * @param string $eq_bank_name  对应龙存管支持开户银行
     * @param string $buss_pic_id 公司营业执照
     * @param string $legal_front_pic_id 法人身份证正面
     * @param string $legal_back_pic_id 法人身份证反面
     * @param string $cert_pic_id 授权书图片ID
     * @param int $role_id 存管企业角色:100:企业店铺、101：企业买家、002:个体工商户店铺、300:交易市场物流企业、310:交易市场仓储企业
     * @param string $registrant 注册人身份，1:法定代表人、2:授权人
     * @param int $acc_type //账户类型，1:对私,2:对公
     */
    public function createAccount($trade_no, $entity_id, $credit_cd, $entity_name, $legal, $legal_id_card, $legal_mobile, $account, $account_name, $bank_name, $eq_bank_name = '', $buss_pic_id, $legal_front_pic_id, $legal_back_pic_id, $cert_pic_id = "", $role_id = "100", $registrant = 1, $acc_type = 2)
    {
        $data = [
            'tradeNo' => $trade_no,
            'platCusNO' => $entity_id,
            'platRoleID' => $role_id,
            'creditCd' => $credit_cd,
            'busFullNm' => $entity_name,
            'registrant' => $registrant,
            'legalPerNm' => $legal,
            'legalPerIdTyp' => '01',
            'legalPerIdNo' => $legal_id_card,
            'mercFlg' => 1,     //商户标识，默认1
            'receAcTyp' => $acc_type,
            'receAc' => $account,
            'receAcName' => $account_name,
            'receAcBankName' => $bank_name,
            'receAcBankNm' => $eq_bank_name ?? $bank_name,
            'recePerId' => $legal_id_card,
            'receMbl' => $legal_mobile,
            'receTyp' => 1, //收款类型（1：非委托收款，2：委托他人收款），默认为1，预开户时只允许为1
            'isAcFlg' => 1,
            'busRating' => '00',
            'bgRetUrl' => $this->config['callback_create_account_url'],
            'bussLicenseID' => $buss_pic_id,
            'legalFrontPic' => $legal_front_pic_id,
            'legalBackPic' => $legal_back_pic_id,
            'certPic' => $cert_pic_id,
            'openAccType' => 1, //电子登记簿开立模式（0:正常，1:存量用户预开立），默认0
        ];
        $result = (new Client($this->config))->merchantCreateBatch($data);
        if (isset($result['info']) || isset($result['body'])) {
            if ($result && in_array($result['body']['rstCode'], ["0", "1"])) {
                return array_merge([
                    'result_code' => "SUCCESS",
                    'return_code' => "SUCCESS",
                ], $result['body']);
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 企业用户信息变更
     * 会有两次异步通知，申请成功，审核成功，依据流水号reqSn识别，所以要记录当时请求的流水号reqSn
     * 或者单独一个接收通知接口
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $mch_code
     * @param $cert_pic_id
     * @param string $oper_type //12:银行账户开户行行号变更、13:银行账户开户行名称变更、14.法人变更、15.银行账号变更、23:被授权人变更
     * @param bool $is_mobile_view
     * @param string $return_url
     */
    public function merchantInfoChangeByWeb($trade_no, $mch_code, $cert_pic_id, $agent, $agent_id_card, $agent_mobile, $oper_type = "23", $is_mobile_view = false, $return_url = "")
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'pageRetUrl' => $return_url, //页面返回url
            'bgRetUrl' => $this->config['callback_update_account_url'],   //后台通知url
            'agent' => $agent,
            'agentIdType' => '01',
            'agentIdNo' => $agent_id_card,
            'agentMbl' => $agent_mobile,
            'certPic' => $cert_pic_id, //被授权书图片ID，上送文件获得，授权书模版找建行业务员要
        ];

        (new Client($this->config))->merchantInfoChange($data, $is_mobile_view);
    }

    /**
     * [API] 企业用户信息变更
     * 待审核、审核回退，审核驳回，审核成功状态都会向平台发送回调
     * 如审核驳回，则该请求被关闭，同一平台用户编号不能再次申请
     * 15.银行账号变更时，如果电子登记簿余额不为0，或存在在途资金，则不允许变更，操作类型28.银行账号强制变更时无需校验
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $mch_code
     * @param string $legal     法人
     * @param string $legal_id_card 法人生份证
     * @param string $legal_mobile  公司法人手机号 [15，28，必填]
     * @param string $account   结算账户 [15，28，必填]
     * @param string $account_name  结算账户名 [15，28，必填]
     * @param string $bank_name     开户银行 [15，28，必填]
     * @param string $eq_bank_name  对应龙存管支持开户银行
     * @param string $legal_front_pic_id    法人身份证正面
     * @param string $legal_back_pic_id     法人身份证反面
     * @param string $change_acc_id 账户变更资料ID，[28，必填]
     * @param string $oper_type 14.法人变更, 15.银行账号变更, 28.银行账号强制变更
     */
    public function merchantInfoChange($trade_no, $mch_code, $legal, $legal_id_card, $legal_mobile = '', $account = '', $account_name = '', $bank_name = '', $eq_bank_name = '', $legal_front_pic_id, $legal_back_pic_id, $change_acc_id = '', $buss_pic_id='', $oper_type = "15")
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'bgRetUrl' => $this->config['callback_update_account2_url'],   //后台通知url
            'accountNm' => $legal,  //持卡人姓名/账户户名/法人真实姓名
        ];

        if ($oper_type == '14') {
            $data = array_merge($data, [
                'legalPerIdTyp' => '01',
                'legalPerIdNo' => $legal_id_card,
                'legalFrontPic' => $legal_front_pic_id,
                'legalBackPic' => $legal_back_pic_id,
            ]);
        }

        if ($oper_type == '15' || $oper_type == '28') {
            $data = array_merge($data, [
                'receAcTyp' => 2,
                'receTyp' => 1,
                'receAc' => $account,
                'receAcName' => $account_name,
                'receAcBankName' => $bank_name,
                'receAcBankNm' => $eq_bank_name ?? $bank_name,
                'recePerId' => $legal_id_card,
                'receMbl' => $legal_mobile,
                'receFrontPic' => $legal_front_pic_id,
                'receBackPic' => $legal_back_pic_id,
            ]);
        }

        if ($oper_type == '28') {
            $data = array_merge($data, [
                'changeAccFile' => $change_acc_id,
            ]);
        }

        //营业执照图片ID
        if ($buss_pic_id) {
            $data = array_merge($data, [
                'bussLicenseID' => $buss_pic_id,
            ]);
        }

        $result = (new Client($this->config))->merchantInfoChangeBatch($data);
        if (isset($result['info']) || isset($result['body'])) {
            if ($result && in_array($result['body']['rstCode'], ["0", "1"])) {
                return array_merge([
                    'result_code' => "SUCCESS",
                    'return_code' => "SUCCESS",
                ], $result['body']);
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 用户电子登记簿状态变更
     * 锁定、解锁：指的是这个用户什么操作都不能做
     * 冻结、解冻：指的是  关于资金类的交易  无法进行
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $mch_code
     * @param string $comments
     * @param string $effDate
     * @param string $expDate
     * @param string $oper_type 操作类型包括：04:锁定、05:解锁、06:冻结、07:解冻
     * @param bool $is_mobile_view 标记是否是移动端访问,默认否
     */
    public function accountStatusChange($trade_no, $mch_code, $comments, $effDate = "", $expDate = "", $oper_type = "04", $is_mobile_view = false)
    {

        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'effectiveFlag' => 'Y', //是否立刻生效，Y:是、N:否
            'effDt' => $effDate ? date('Ymd', strtotime($effDate)) : date('Ymd'), //生效日期，当为立刻生效时，此栏位允许为空
            'effTm' => $effDate ? date('His', strtotime($effDate)) : date('His'), //生效时间，当为立刻生效时，此栏位允许为空
            'expDt' => $effDate ? date('Ymd', strtotime($expDate)) : '20990101', //失效日期 允许为空
            'expTm' => $effDate ? date('His', strtotime($expDate)) : '000000', //失效时间 允许为空
            'rmk1' => $comments, //变更原因
        ];
        $result = (new Client($this->config))->accountStatusChange($data, $is_mobile_view);
        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return $result['body'];
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }


    /**
     * 交易密码重置
     *
     * @param string $trade_no 交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $mch_code
     * @param string $oper_type 17:交易密码修改、18:交易密码重置
     * @param bool $is_mobile_view 标记是否是移动端访问,默认否
     * @param string $return_url
     */
    public function restPwdByWeb($trade_no, $mch_code, $oper_type = "17", $is_mobile_view = false, $return_url = "")
    {

        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'pageRetUrl' => $return_url, //页面返回url
            'bgRetUrl' => $this->config['callback_rest_pwd_url'],   //后台通知url
        ];
        (new Client($this->config))->passwordSetting($data, $is_mobile_view);
    }

    /**
     * 账户激活
     *
     * @param string    $trade_no  交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param $mch_code
     * @param string    $oper_type 29:电子登记簿激活
     * @param bool  $is_mobile_view  标记是否是移动端访问,默认否
     * @param string    $return_url
     */
    public function accountActByWeb($trade_no, $mch_code, $oper_type = "29", $is_mobile_view = false, $return_url = "")
    {

        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'operType' => $oper_type,
            'pageRetUrl' => $return_url, //页面返回url
            'bgRetUrl' => $this->config['callback_rest_pwd2_url'],   //后台通知url
        ];
        (new Client($this->config))->passwordSetting($data, $is_mobile_view);
    }

    /**
     * 查询账户信息
     * @param   string $trade_no    交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param   string $entity_id   单位ID
     * @param   string $role_id     单位角色ID，默认100:企业店铺
     * @return  array               返回数组
     */
    public function merchantInfoQuery($trade_no, $entity_id, $role_id = "100")
    {
        $data = [
            'tradeNo' => $trade_no,
            'platCusNO' => $entity_id,
            'platRoleID' => $role_id,
        ];

        $result = (new Client($this->config))->userInfoQuery($data);

        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return array_merge([
                    'result_code' => "SUCCESS",
                    'return_code' => "SUCCESS",
                ], $result['body']);
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 查询账户状态
     * @param   string $trade_no    交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param   string $mch_code    龙存管商户编号
     * @return  array               返回数组
     */
    public function merchantStatusQuery($trade_no, $mch_code)
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
        ];

        $result = (new Client($this->config))->acntStatusQuery($data);

        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return array_merge([
                    'result_code' => "SUCCESS",
                    'return_code' => "SUCCESS",
                ], $result['body']);
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 打款认证信息查询
     * @param   string $trade_no    交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param   string $mch_code    龙存管商户编号
     * @return  array               返回数组
     */
    public function payAuthInfoQuery($trade_no, $mch_code)
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
        ];

        $result = (new Client($this->config))->payAuthInfoQuery($data);

        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return array_merge([
                    'result_code' => "SUCCESS",
                    'return_code' => "SUCCESS",
                ], $result['body']);
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
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
        return (new Client($this->config))->asynchroNotice($xml);
    }

    /**
     * 退款交易查询
     * 交易状态，包括:0:退款成功、1:退款处理中、2:退款失败、5:取消退款
     *
     * @param string $trade_no      交易流水号，随机生成, 每次请求都必须有, 建议用公共方法生成
     * @param string $out_refund_no 退款订单单号
     * @return array|mixed
     */
    public function refundQuery($trade_no, $out_refund_no)
    {
        $data = [
            'tradeNo' => $trade_no,
            'refundOrdNo' => $out_refund_no,
        ];
        $result = (new Client($this->config))->refundQuery($data);
        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return array_merge([
                    'result_code' => "SUCCESS",
                    'return_code' => "SUCCESS",
                ], $result['body']);
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 发货通知
     * @param   string $trade_no [交易流水号]
     * @param   string $mch_code [龙存管商户号]
     * @param   string $out_trade_no [交易订单号]
     * @param   string $trxType [业务类型,12003:商城收货确认]
     * @param   string $remark [备注]
     * @return  array|mixed
     */
    public function goodsNotice($trade_no, $mch_code, $out_trade_no, $trxType = '12003', $remark = '系统发货')
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'trxType' => $trxType,
            'oriOrdNo' => $out_trade_no,
            'tradeOrdNo' => $out_trade_no,
            'remark' => $remark,
            'fflag' => 1,
        ];
        $result = (new Client($this->config))->goodsNotice($data);
        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return [
                    'result_code' => 'SUCCESS',
                    'return_code' => 'SUCCESS',
                    'jrnno' => $result['body']['jrnno'],
                ];
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 超时后平台代为确认收货
     * @param   string $trade_no [交易流水号]
     * @param   string $mch_code [龙存管商户号]
     * @param   string $jrnno [龙存管订单流水号]
     * @param   string $out_trade_no [交易订单号]
     * @param   string $total_fee [交易金额]
     * @param   string $trxType [业务类型,12010:平台确认收货]
     * @return  array|mixed
     */
    public function insteadToConfirm($trade_no, $mch_code, $jrnno, $out_trade_no, $total_fee, $trxType = '12010')
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'trxType' => $trxType,
            'jrnno' => $jrnno,
            'ordNo' => $out_trade_no,
            'traAmt' => $total_fee,
            'tradt' => date('Ymd'),
            'tratm' => date('His'),
        ];
        $result = (new Client($this->config))->insteadToConfirm($data);
        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return [
                    'result_code' => 'SUCCESS',
                    'return_code' => 'SUCCESS',
                    'jrnno' => $result['body']['jrnno'],
                    'success_time' => $result['body']['tradt'] . $result['body']['tratm'],
                ];
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 平台确认退款申请
     * @param   string $trade_no                [交易流水号]
     * @param   string $jrnno                   [退款申请流水号]
     * @param   string $agreest                 [确认状态,Y:同意退款、N:不同意退款]
     * @return  array|mixed
     */
    public function platConfirmToRefund($trade_no, $jrnno, $agreest = 'Y')
    {
        $data = [
            'tradeNo' => $trade_no,
            'agreest' => $agreest,
            'jrnno' => $jrnno,
        ];
        $result = (new Client($this->config))->platConfirmToRefund($data);
        if (isset($result['info']) || isset($result['body'])) {
            if ($result && $result['body']['rstCode'] == "0") {
                return array_merge([
                    'result_code' => 'SUCCESS',
                    'return_code' => 'SUCCESS',
                ], $result['body']);
            } else {
                return ['err_code' => $result['info']['retCode'], "err_code_des" => $result['info']['errMsg'] . "(" . $trade_no . ")"];
            }
        } else {
            return ['err_code' => $result['returnCode'], "err_code_des" => $result['returnMessage']];
        }
    }

    /**
     * 用户确认
     * @param   string $trade_no [交易流水号]
     * @param   string $mch_code [龙存管商户号]
     * @param   string $jrnno [龙存管订单流水号]
     * @param   string $trxType [业务类型,12003:商城收货确认，12008:商品退款，12014:佣金退款；]
     * @param   string $agreest [交易确认状态,Y:同意、N:不同意/延迟确认]
     * @param   string $return_url [页面返回url]
     * @return  array|mixed
     */
    public function userToConfirmByWeb($trade_no, $mch_code, $jrnno, $trxType = '12008', $agreest = 'Y', $return_url = '')
    {
        $data = [
            'tradeNo' => $trade_no,
            'mbrCode' => $mch_code,
            'trxType' => $trxType,
            'jrnno' => $jrnno, //如果是收货确认，用发货通知的交易单号，如果是退款，用退款申请的交易单号
            'agreest' => 'Y',
            'pageRetUrl' => $return_url,
            'bgRetUrl' => $this->config['callback_user_confirm_url'],
        ];
        (new Client($this->config))->userToConfirm($data);
    }
}