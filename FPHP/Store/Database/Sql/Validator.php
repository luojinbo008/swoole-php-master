<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/7
 * Time: 17:38
 */

namespace FPHP\Store\Database\Sql;


class Validator
{
    public static function realEscape($value, $callback = null)
    {
        if (null != $callback && is_object($callback)) {
            return call_user_func($callback, $value);
        }
        return addslashes($value);
    }
}