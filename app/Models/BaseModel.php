<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/8/25
 * Time: 7:28 下午
 * Created by SANewOrangePHP制作委员会.
 */
namespace App\Models;

use App\Enums\ImagePathEnum;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    /*
     * 图片的地址拼接
     */
    protected function prefixImgUrl($url, $from)
    {
        if ($from == ImagePathEnum::localHost) {
            $url = ImagePathEnum::localHostPath . $url;
        } elseif ($from == ImagePathEnum::oss) {
            $url = ImagePathEnum::ossPath . $url;
        }
        return $url;
    }

}
