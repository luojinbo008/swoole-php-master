<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 16:10
 */

namespace FPHP\Foundation\Core;


use FPHP\Foundation\Exception\System\InvalidArgumentException;

class RunMode
{
    private static $modeMap  = [
        'dev'       => 1,
        'online'    => 2,
    ];
    private static $runMode = null;
    private static $cliInput = null;

    public static function get()
    {
        return self::$runMode;
    }

    public static function set($runMode)
    {
        if (!isset(self::$modeMap[$runMode])) {
            throw new InvalidArgumentException('invalid runMode in RunMode::set');
        }
        self::$runMode = $runMode;
    }

    public static function setCliInput($mode)
    {
        if (!$mode) {
            return false;
        }
        if (!isset(self::$modeMap[$mode])) {
            throw new InvalidArgumentException('invalid runMode from cli');
        }
        self::$cliInput = $mode;
    }

    public static function detect()
    {
        if (null !== self::$cliInput) {
            self::$runMode = self::$cliInput;
            return true;
        }
        $envInput = getenv('FPHP_RUN_MODE');
        if (isset(self::$modeMap[$envInput])) {
            self::$runMode = $envInput;
            return true;
        }
        $iniInput = get_cfg_var('FPHP.RUN_MODE');
        if (isset(self::$modeMap[$iniInput])) {
            self::$runMode = $iniInput;
            return true;
        }
        self::$runMode = 'online';
    }
}