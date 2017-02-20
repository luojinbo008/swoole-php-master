<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 14:56
 */
namespace FPHP\Foundation\Core;
use FPHP\Util\Types\Arr;

class Config
{
    private static $configMap = [];

    public static function init()
    {
        $runMode = RunMode::get();
        $path = Path::getConfigPath();

        $sharePath = $path . 'share/';
        $shareConfigMap = ConfigLoader::getInstance()->load($sharePath);

        $runModeConfigPath = Path::getConfigPath() . $runMode;
        $runModeConfig = ConfigLoader::getInstance()->load($runModeConfigPath);

        self::$configMap = Arr::merge(self::$configMap, $shareConfigMap, $runModeConfig);

        //  add private dir
        if ('dev' == $runMode) {
            $privatePath = Path::getConfigPath() . '.private/';
            if (is_dir($privatePath)) {
                $privateConfig = ConfigLoader::getInstance()->load($privatePath);
                self::$configMap = Arr::merge(self::$configMap, $privateConfig);
            }
        }
    }

    public static function get($key, $default = null)
    {
        if (!$key) {
            return $default;
        }
        $preKey = $key;
        $routes = explode('.', $key);
        if (empty($routes)) {
            return $default;
        }
        $result = &self::$configMap;
        $hasConfig = true;
        foreach ($routes as $route) {
            if (!isset($result[$route])) {
                $hasConfig = false;
                break;
            }
            $result = &$result[$route];
        }
        if (!$hasConfig) {
            return $default;
        }
        return $result;
    }

    public static function set($key, $value)
    {
        $routes = explode('.', $key);
        if (empty($routes)) {
            return false;
        }
        $newConfigMap = Arr::createTreeByList($routes, $value);
        self::$configMap = Arr::merge(self::$configMap, $newConfigMap);
        return true;
    }

    public static function clear()
    {
        self::$configMap = [];
    }
}