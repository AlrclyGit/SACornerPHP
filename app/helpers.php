<?php
/**
 * Name: 应用公共文件
 * User: 萧俊介
 * Date: 2020/9/1
 * Time: 11:27 上午
 * Created by SANewOrangePHP制作委员会.
 */

/**
 *
 */
function curlGet($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // 不校验
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * 生成N位的随机字符串，默认16位
 */
function getRandChar($length = 16)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

/**
 * 正常的返回值
 */
function saReturn($data = [],$code = 0, $msg ='YesOK')
{
    $result = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
    return response()->json($result, 200);
}
