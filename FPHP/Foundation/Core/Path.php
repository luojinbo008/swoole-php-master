<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 16:24
 */

namespace FPHP\Foundation\Core;

use FPHP\Util\Types\Dir;

class Path {

    const DEFAULT_CONFIG_PATH       = 'resource/config/';
    const DEFAULT_ROUTING_PATH      = 'resource/routing';
    const DEFAULT_MIDDLEWARE_PATH   = 'resource/middleware';
    const DEFAULT_CACHE_PATH        = 'resource/cache/';
    const DEFAULT_SQL_PATH          = 'resource/sql/';
    const DEFAULT_TABLE_PATH        = 'resource/config/share/table/';

    const ROOT_PATH_CONFIG_KEY          = 'path.root';
    const CONFIG_PATH_CONFIG_KEY        = 'path.config';
    const ROUTING_PATH_CONFIG_KEY       = 'path.routing';
    const MIDDLEWARE_PATH_CONFIG_KEY    = 'path.middleware';
    const CACHE_PATH_CONFIG_KEY         = 'path.cache';
    const SQL_PATH_CONFIG_KEY           = 'path.sql';
    const TABLE_PATH_CONFIG_KEY         = 'path.table';

    private static $configPath     = null;
    private static $rootPath       = null;
    private static $routingPath    = null;
    private static $middlewarePath = null;
    private static $cachePath      = null;
    private static $sqlPath        = null;
    private static $tablePath      = null;

    public static function init($rootPath)
    {
        self::setRootPath($rootPath);
        self::setOtherPathes();
        self::setInConfig();
    }

    public static function getRootPath()
    {
        return self::$rootPath;
    }

    public static function getConfigPath()
    {
        return self::$configPath;
    }

    public static function setConfigPath($configPath)
    {
        self::$configPath = $configPath;
    }

    public static function getSqlPath()
    {
        return self::$sqlPath;
    }

    public static function getCachePath()
    {
        return self::$cachePath;
    }

    private static function setRootPath($rootPath)
    {
        self::$rootPath = Dir::formatPath($rootPath);
    }

    public static function getTablePath()
    {
        return self::$tablePath;
    }

    private static function setOtherPathes()
    {
        self::$configPath       = self::$rootPath . self::DEFAULT_CONFIG_PATH;
        self::$routingPath      = self::$rootPath . self::DEFAULT_ROUTING_PATH;
        self::$middlewarePath   = self::$rootPath . self::DEFAULT_MIDDLEWARE_PATH;
        self::$cachePath        = self::$rootPath . self::DEFAULT_CACHE_PATH;
        self::$sqlPath          = self::$rootPath . self::DEFAULT_SQL_PATH;
        self::$tablePath      = self::$rootPath . self::DEFAULT_TABLE_PATH;
    }

    private static function setInConfig()
    {
        Config::set(self::ROOT_PATH_CONFIG_KEY, self::$rootPath);
        Config::set(self::CONFIG_PATH_CONFIG_KEY, self::$configPath);
        Config::set(self::ROUTING_PATH_CONFIG_KEY, self::$routingPath);
        Config::set(self::MIDDLEWARE_PATH_CONFIG_KEY, self::$middlewarePath);
        Config::set(self::CACHE_PATH_CONFIG_KEY, self::$cachePath);
        Config::set(self::SQL_PATH_CONFIG_KEY, self::$sqlPath);
        Config::set(self::TABLE_PATH_CONFIG_KEY, self::$tablePath);
    }
}