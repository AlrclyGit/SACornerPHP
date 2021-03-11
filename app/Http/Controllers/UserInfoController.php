<?php
/**
 * Name: 用户信息控制器
 * User: 萧俊介
 * Date: 2020/11/3
 * Time: 3:06 下午
 * Created by PHP制作委员会.
 */

namespace App\Http\Controllers;


use App\Exceptions\UserException;
use App\Models\User;
use App\Service\TokenService;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    /*
     * 获取用户信息
     */
    public function getUserInfo()
    {
        // 获取当前用户UID
        $uid = TokenService::getCurrentUid();
        // 从 User 表获取用户信息和地址
        $user = User::find($uid);
        // 判断用户地址是否存在
        if (!$user['nick_name']) {
            throw new UserException([
                'msg' => '用户昵称信息缺失',
                'errorCode' => '30002'
            ]);
        }
        // 返回成功消息
        return saReturn($user);
    }

    /*
     * 写入用户信息
     */
    public function setUserInfo(Request $request)
    {
        $wxUserInfo = $request->all();
        $this->verify($wxUserInfo);
        return saReturn();
    }

    /*
     * 数据签名校验
     */
    private function verify($wxUserInfo)
    {
        // 获取当前用户UID
        $uid = TokenService::getCurrentUid();
        // 从 User 表获取用户信息
        $user = User::find($uid);
        // 签名
        $signature = sha1($wxUserInfo['rawData'] . $user['session_key']);
        // 比对签名
        if ($signature == $wxUserInfo['signature']) {
            $userInfo = json_decode($wxUserInfo['rawData'], true);
            $user->nick_name = $userInfo['nickName'];
            $user->avatar_url = $userInfo['avatarUrl'];
            $user->gender = $userInfo['gender'];
            $user->province = $userInfo['province'];
            $user->city = $userInfo['city'];
            $user->country = $userInfo['country'];
            $user->save();
        } else {
            throw new UserException([
                'msg' => '用户信息签名校验失败',
                'errorCode' => '30003'
            ]);
        }
    }
}