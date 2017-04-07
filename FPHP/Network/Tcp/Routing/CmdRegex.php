<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/4/1
 * Time: 17:00
 */

namespace FPHP\Network\Tcp\Routing;


class CmdRegex
{

    public static function decode($cmdname, $rules = [])
    {
        $return = false;
        if (!$rules) return false;
        foreach ($rules as $regex => $route) {
            if ($regex == $cmdname) {
                $return = [
                    'route' => $route,
                ];
                break;
            }
        }
        return $return;
    }

}