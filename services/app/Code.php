<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-8
 * Time: 下午2:33
 */


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
/**
 * 注册
 */
define('API_USER_INVITE_EQUAL',1007);//注册手机号跟邀请人手机号相等
define('API_PHONE_CODE_ERROR',1008);//手机验证码错误
define('API_NO_INVITE',1009);//邀请人帐号不存在
define('API_NO_PWD',1010);//密码为空
define('API_NO_REPWD',1011);//确认密码为空
define('API_NO_EQUAL_PWD_REPWD',1012);//确认密码和密码不相等

