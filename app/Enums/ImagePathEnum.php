<?php
/**
 * Name: 图片地址设置
 * User: 萧俊介
 * Date: 2020/8/25
 * Time: 8:29 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Enums;


class ImagePathEnum
{

    // 图片地址类型
    const localHost = 1;
    const oss = 2;

    // 图片地址URL
    const localHostPath = 'https://api.shop.caisurf.com/';
    const ossPath = 'https://oss.caisurf.com/';

}
