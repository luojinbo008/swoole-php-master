<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 17:17
 */

namespace FPHP\Foundation\Container;

use RuntimeException;
use FPHP\Testing\Stub;

class Di
{
    /**
     * @var Container $instance
     */
    protected static $instance;

    /**
     * @param Container $instance
     */
    public static function resolveFacadeInstance(Container $instance)
    {
        static::$instance = $instance;
    }

    /**
     * @param $abstract
     * @param array $parameters
     * @param bool $shared
     * @return mixed|object
     */
    public static function make($abstract, array $parameters = [], $shared = false) {
        return static::$instance->make($abstract, $parameters, $shared);
    }

    /**
     * @param Stub $stub
     */
    public static function addStub(Stub $stub)
    {
        return static::$instance->addStub($stub);
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::$instance;
        if (! $instance) {
            throw new RuntimeException('A facade instance has not been set.');
        }
        switch (count($args)) {
            case 0:
                return $instance->$method();
            case 1:
                return $instance->$method($args[0]);
            case 2:
                return $instance->$method($args[0], $args[1]);
            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);
            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}