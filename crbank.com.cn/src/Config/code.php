<?php

/**
 * 平台错误码相关配置
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/6
 */


return [
    "SYSTEMERROR" => "接口返回错误",//系统超时
    "PARAM_ERROR" => "参数错误",//请求参数未按指引改进
    "REQUIRE_POST_METHOD" => "请使用post方法",//未使用post传递参数
    "SIGNERROR" => "签名错误",//参数签名结果不正确
    "XML_FORMAT_ERROR" => "XML格式错误",//XML格式错误
    "PLAT_NOT_EXAM" => "接口返回错误",//平台商未审核通过
    "MCH_NOT_EXAM" => "接口返回错误",//商户未审核错误
    "MCH_INFO_IS_NULL" => "商户号错误",//商户号不存在
    "MCH_BASIC_IS_NOT_EXIST" => "商户基础信息不存在",//商户基础信息不存在
];