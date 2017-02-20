<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/15
 * Time: 14:54
 */

namespace FPHP\Util\Types;


class Time
{
    private $timeStamp = null;

    public function __construct($timeStamp = null)
    {
        if (null !== $timeStamp && is_int($timeStamp)) {
            $this->timeStamp = $timeStamp;
            return true;
        }
        $this->timeStamp = time();
    }

    public static function current($format=false)
    {
        $timeStamp = time();
        if (true === $format) {
            return $timeStamp;
        }
        if(false === $format){
            return date('Y-m-d H:i:s',$timeStamp);
        }
        return date($format,$timeStamp);
    }

    public static function stamp()
    {
        return self::current(true);
    }

}