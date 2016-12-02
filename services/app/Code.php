<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-8
 * Time: 下午2:33
 */

define('PHONE_CODE_TIMEOUT',       60);//验证码过期时间
/**
 * 用token进行登录
 */
define('API_LOGIN_FAILED_INVALID_TOKEN',       1001);//无效的token
define('API_TOKEN_NOT_FOUND',       1002);//此token不存在
define('API_USER_DISABLE',       1003);//此用户已被禁用
define('API_TOKEN_EXPIRE',       1004);//token已过期
/**
 * 手机号登录
 */
define('ERR_LOGIN_FAIL_PHONE',       1005);//登录失败,无效手机号
define('ERR_LOGIN_FAIL_PWD_OR_ACCOUNT',       1006);//登录失败,不存在此手机号或密码错误
define('API_IS_NOT_VIP',       1035);//此用户没有激活
define('API_USER_IS_VIP',       1036);//此用户已激活

/**
 * 注册
 */
define('API_USER_INVITE_EQUAL',1007);//注册手机号跟邀请人手机号相等
define('API_PHONE_CODE_ERROR',1008);//手机验证码错误
define('API_NO_INVITE',1009);//邀请人帐号不存在
define('API_NO_PWD',1010);//密码为空
define('API_NO_REPWD',1011);//确认密码为空
define('API_NO_EQUAL_PWD_REPWD',1012);//确认密码和密码不相等
define('API_EXISTED_PHONE',1013);//此手机号已被注册
define('API_GET_CODE_FAIL',1014);//获取验证码失败
define('API_REG_VALIDATE_TOKEN_FAIL',1015);//获取验证码token验证失败
define('API_SEND_CODE_QUICK',1016);//发送验证码频率过快
define('API_SEND_PHONE_DAY_EXCEED_LIMIT',1017);//此号码已超出单日发送限制
define('API_SEND_DAY_EXCEED_LIMIT',1018);//超出单日发送限制
/**
 * 参数限制
 */
define('API_MISSING_PARAMETER',       1019);//缺少必要参数
/**
 * 无效请求资源
 */
define('API_RESOURCES_NOT_FOUND',       1020);//请求资源不存在
/**
 * 评论
 */
define('API_SHOP_NOT_FOUND',       1021);//商家不存在
define('API_COMMENT_NOT_NULL',       1026);//商家不存在
define('API_COMMENT_FAIL',       1027);//评价失败
define('API_GOOD_NOT_FOUND',       1028);//评价的商品不存在
define('API_ORDER_NOT_FOUND',       1029);//此订单不存在
define('API_GOOD_SKU_NOT_FOUND',       1030);//此商品的规格不存在

/**
 * 上传图片
 */
define('API_IMAGE_TYPE_ERROR',       1022);//图片格式错误
define('API_IMAGE_SIZE_ERROR',       1023);//图片大小错误，最大为2M
define('API_UPLOAD_RESOURCES_NULL',       1024);//上传资源为空
define('API_IMAGE_WRITE_FAIL',       1025);//上传资源写入失败

/**
 * 订单类
 */
define('API_SHIPPING_NOT_FOUND',       1031);//发货地不存在
define('API_CART_NOT_FOUND',       1032);//购物车不存在
define('API_USER_ADDR_NOT_FOUND',       1033);//收货地址不存在
define('API_AREA_NOT_FOUND',       1034);//地区不存在
/**
 *
 */
define('API_USER_CREDIT_NO_ENOUGH',       1037);//帮帮币不足