<?php

/**
 * Name: 专题控制器
 * User: 萧俊介
 * Date: 2020/8/27
 * Time: 1:03 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Controllers;

use App\Exceptions\ThemeExceptions;
use App\Http\Requests\IDCollection;
use App\Http\Requests\IDMustBePositiveInt;
use App\Models\Theme;

class ThemeController extends Controller
{

    /*
    * 获取任意组合的专题信息
    * $url /theme?ids=id1,id2,id3
    * @return 一组theme模型
    */
    public function getSimpleList(IDCollection $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 将字符串参数转成数组
        $ids = explode(',', $validated['ids']);
        // 通过ID组获取对应的专题
        $result = Theme::getThemeByIds($ids);
        // 错误处理与返回
        if ($result->isEmpty()) {
            throw new ThemeExceptions();
        }
        return $result;
    }

    /*
     * 获取某一专题的商品信息
     * @url /theme:id
     */
    public function getComplexOne(IDMustBePositiveInt $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 通过ID获取对应专题详细产品
        $result = Theme::getThemeWithProducts($validated['id']);
        // 错误处理与返回
        if (!$result) {
            throw new ThemeExceptions();
        }
        return $result;
    }

}
