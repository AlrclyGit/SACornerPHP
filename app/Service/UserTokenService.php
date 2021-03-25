<?php
/**
 * Name: 用户Token生成服务
 * User: 萧俊介
 * Date: 2020/9/1
 * Time: 11:18 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Service;


use App\Enums\ScopeEnum;
use App\Exceptions\TokenException;
use App\Models\User;

class UserTokenService extends TokenService
{

    protected $code;
    protected $wxAppID;
    protected $weAppSecret;
    protected $wxLoginUrl;

    /*
     * 构造方法
     */
    function __construct($code)
    {
        $wxUrl = "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_cod";
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->weAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf($wxUrl, $this->wxAppID, $this->weAppSecret, $this->code);
    }

    /*
     * 获取 Token ，通过 Code 获取用户 Openid
     */
    public function get()
    {
        // 进行 CURL 请求
        $result = curlGet($this->wxLoginUrl);
        // 将返回结果转成数组
        $wxResult = json_decode($result, true);
        // 如果结果为空
        if (empty($wxResult)) {
            throw new TokenException(91001, '获取session_key及openID时异常，微信内部错误', $wxResult);
        } else {
            // 如果结果存在错误
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                throw new TokenException(91002, 'code可能是无效的', $wxResult);
            }
        }
        // 传入获取 Token 的方法并返回
        return $this->grantToken($wxResult);


    }

    /*
     * 通过Openid生成用户用Uid，并换取Token
     */
    private function grantToken($wxResult)
    {
        // 查询用户是否存在
        $user = User::where(['openid' => $wxResult['openid']])->first();
        // 存在则更新session_key,否则添加用户数据
        if ($user) {
            $user->session_key = $wxResult['session_key'];
            $user->save();
        } else {
            $user = new User;
            $user->openid = $wxResult['openid'];
            $user->session_key = $wxResult['session_key'];
            $user->save();
        }
        // 生成 Token 的 Value
        $cachedValue = $this->prepareTokenValue($user->id, 3600);
        // 生成 Token 的 签名并返回
        return $this->produceToken($cachedValue);
    }

    /*
     * 生成 Token 的 参数部分
     */
    private function prepareTokenValue($uid, $expires_in)
    {
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::User;
        $cacheValue['expire'] = time() + $expires_in;
        return $cacheValue;
    }


}
