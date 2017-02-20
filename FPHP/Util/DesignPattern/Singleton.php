<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/6
 * Time: 17:23
 */

namespace FPHP\Util\DesignPattern;


trait Singleton
{

    /**
     * @var static
     */
    private static $_instance = null;

    /**
     * @return static
     */
    final public static function instance()
    {
        return static::singleton();
    }

    final public static function singleton()
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    /**
     * @return static
     */
    final public static function getInstance()
    {
        return static::singleton();
    }

    final public static function swap($instance)
    {
        static::$_instance = $instance;
    }

}