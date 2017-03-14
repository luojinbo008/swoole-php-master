<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/22
 * Time: 16:43
 */
namespace FPHP\Store\Facade;

use FPHP\Store\NoSQL\Flow;
use RuntimeException;
use FPHP\Network\Connection\ConnectionManager;
use FPHP\Store\NoSQL\Redis\RedisManager;

class Cache
{
    private static $redis = null;

    private static $cacheMap = null;

    public static function init(array $cacheMap)
    {
        self::$cacheMap = $cacheMap;
    }

    public static function get($configKey, $keys)
    {
        $flow = new Flow();
        $cacheKey = self::getConfigCacheKey($configKey);
        $realKey = self::getRealKey($cacheKey, $keys);
        $sid = self::getRedisConnByConfigKey($configKey);
        yield $flow->get($sid, $realKey);
        return ;
    }

    public static function set($configKey, $value, $keys)
    {
        $flow = new Flow();
        $cacheKey = self::getConfigCacheKey($configKey);
        $realKey = self::getRealKey($cacheKey, $keys);
        $sid = self::getRedisConnByConfigKey($configKey);
        if (!empty($realKey)) {
            $result = (yield $flow->set($sid, $realKey, $value, $cacheKey['exp']));
            yield $result;
        }
    }

    public static function expire($configKey, $keys, $expire = 0)
    {
        $flow = new Flow();
        $cacheKey = self::getConfigCacheKey($configKey);
        $realKey = self::getRealKey($cacheKey, $keys);
        $sid = self::getRedisConnByConfigKey($configKey);
        if (!empty($realKey)) {
            $result = (yield $flow->expire($sid, $realKey, $expire));
            yield $result;
        }
    }

    private static function getRedisConnByConfigKey($configKey)
    {
        $pos = strrpos($configKey, '.');
        $subPath = substr($configKey, 0, $pos);
        $config = self::getConfigCacheKey($subPath);
        if (!isset($config['common'])) {
            throw new RuntimeException('connection path config not found');
        }
        return $config['common']['connection'];
    }

    private static function getRealKey($config, $keys)
    {
        $format = $config['key'];
        if ($keys === null) {
            return $format;
        }
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        $key = call_user_func_array('sprintf', array_merge([$format], $keys));
        return $key;
    }

    private static function getConfigCacheKey($configKey)
    {
        $result = self::$cacheMap;
        $routes = explode('.', $configKey);
        if (empty($routes)) {
            return null;
        }
        foreach ($routes as $route) {
            if (!isset($result[$route])) {
                return null;
            }
            $result = &$result[$route];
        }
        return $result;
    }
}