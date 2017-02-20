<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 10:29
 */
namespace FPHP\Foundation\Container;

use ReflectionClass;
use FPHP\Testing\Stub;

class Container
{
    protected $mockInstances = [];
    protected $instances = [];

    /**
     * 获得 实例
     *
     * @param $abstract
     * @return mixed|null
     */
    public function get($abstract)
    {
        $abstract = $this->normalize($abstract);
        if (isset($this->mockInstances[$abstract])) {
            return $this->mockInstances[$abstract];
        }
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        return null;
    }

    /**
     * 设置 实例
     * @param $alias
     * @param $instance
     */
    public function set($alias, $instance)
    {
        if (!isset($this->instances[$alias])) {
            $this->instances[$alias] = $instance;
        }
    }

    /**
     * 设置 模拟实例
     * @param $abstract
     * @param $instance
     */
    public function setMockInstance($abstract, $instance)
    {
        $abstract = $this->normalize($abstract);

        if (!isset($this->mockInstances[$abstract])) {
            $this->mockInstances[$abstract] = $instance;
        }
    }

    /**
     * 设置 存根
     *
     * @param Stub $stub
     */
    public function addStub(Stub $stub)
    {
        $className = $stub->getRealClassName();
        $this->setMockInstance($className, $stub);
    }

    /**
     * 返回 单例模式
     *
     * @param $abstract
     * @param array $parameters
     * @return mixed|object
     */
    public function singleton($abstract, array $parameters = [])
    {
        $abstract = $this->normalize($abstract);
        if (isset($this->mockInstances[$abstract])) {
            return $this->mockInstances[$abstract];
        }
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        $class = new ReflectionClass($abstract);
        $object = $class->newInstanceArgs($parameters);
        if ($object !== null) {
            $this->instances[$abstract] = $object;
        }
        return $object;
    }

    /**
     * 工厂模式
     * @param $abstract
     * @param array $parameters
     * @param bool $shared
     * @return mixed|object
     */
    public function make($abstract, array $parameters = [], $shared = false)
    {
        $abstract = $this->normalize($abstract);
        if (isset($this->mockInstances[$abstract])) {
            return $this->mockInstances[$abstract];
        }
        if ($shared && isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        $class = new ReflectionClass($abstract);
        $object = $class->newInstanceArgs($parameters);
        if ($shared && $object !== null) {
            $this->instances[$abstract] = $object;
        }
        return $object;
    }

    /**
     * 标准化 className
     * @param $className
     * @return string
     */
    protected function normalize($className)
    {
        return is_string($className) ? ltrim($className, '\\') : $className;
    }
}