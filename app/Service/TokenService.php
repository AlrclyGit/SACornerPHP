<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/1
 * Time: 11:18 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Service;


use App\Enums\ScopeEnum;
use App\Exceptions\ForbiddenException;
use App\Exceptions\TokenException;
use Illuminate\Support\Facades\Request;

class TokenService
{

    /*
     * 生成签名的方法
     */
    static private function getSignature($header, $value)
    {
        return md5(md5($header . $value) . config('setting.token_salt'));
    }

    /*
     * 生成一个 JWT 规范的 Token
     */
    protected function produceToken($cacheValue)
    {
        // Header数据
        $header = [
            'alg' => 'md5',
            'typ' => 'JWT'
        ];
        // Header
        $header = base64_encode(json_encode($header));
        // Payload
        $payload = base64_encode(json_encode($cacheValue));
        // Signature
        $signature = self::getSignature($header, $payload);
        // 拼合 Token 数据
        return implode('.', [$header, $payload, $signature]);
    }


    /*
     * 获取用户某个Value
     */
    static public function getCurrentToKenVar($key)
    {
        // 获取 Token 原始数据
        $token = Request::header('token');
        if (empty($token)) {
            throw new TokenException([
                'msg' => '检测到token为空'
            ]);
        }
        // Token 原始数据转为数组
        $tokenArray = explode('.', $token);
        $header = $tokenArray[0];
        $payload = $tokenArray[1];
        $signature = $tokenArray[2];
        // 验证数据的合法性
        $localSignature = self::getSignature($header, $payload);
        if ($localSignature !== $signature) {
            throw new TokenException([
                'msg' => '无效Token'
            ]);
        }
        // payload 部分转为数组
        $value = json_decode(base64_decode($payload), true);
        // 效验 Token 是否已经过期
        if ($value['expire'] < time()) {
            throw new TokenException([
                'msg' => 'Token已过期'
            ]);
        }
        // 查询数组里时候有该值
        if (!array_key_exists($key, $value)) {
            throw new TokenException([
                'msg' => '尝试获取的Token变量不存在'
            ]);
        }
        // 返回
        return $value[$key];
    }

    /*
     * 获取用户Uid
     */
    static public function getCurrentUid()
    {
        $uid = self::getCurrentToKenVar('uid');
        return $uid;
    }

    /*
     * 检测传入的UID是否为当前用户
     */
    static public function isValidOperate($checkedUID)
    {
        if (!$checkedUID) {
            throw new TokenException('检测UID时必须传入一个被检测的UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if ($currentOperateUID == $checkedUID) {
            return true;
        }
        return false;
    }

    /*
     * 权限验证方法（用户）
     */
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentToKenVar('scope');
        $expire = self::getCurrentToKenVar('expire');
        if ($scope) {
            if($expire < time()){
                throw new TokenException();
            }
            if ($scope == ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    /*
     * 权限验证方法（管理员和用户）
     */
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentToKenVar('scope');
        $expire = self::getCurrentToKenVar('expire');
        if ($scope) {
            if($expire < time()){
                throw new TokenException();
            }
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

}
