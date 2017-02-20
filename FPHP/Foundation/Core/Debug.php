<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 14:56
 */
namespace FPHP\Foundation\Core;

class Debug
{

    private static $debug = null;
    private static $cliInput = null;

    public static function get()
    {
        return self::$debug;
    }

    public static function setCliInput($mode)
    {
        self::$cliInput == $mode ? true : false;
    }

    public static function detect()
    {
        if (null !== self::$cliInput) {
            self::$debug = self::$cliInput;
            return true;
        }
        $iniInput = get_cfg_var('FPHP.DEBUG');
        if ($iniInput) {
            self::$debug = true;
            return true;
        }
        self::$debug = false;
    }
}