<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 9:59
 */

namespace FPHP\Network\Http\Routing;

use FPHP\Util\Types\Arr;
use FPHP\Util\Types\Dir;
use FPHP\Util\DesignPattern\Singleton;
use FPHP\Foundation\Core\Config;

class UrlRule {

    use Singleton;

    private static $rules = [];

    public static function loadRules()
    {
        $routeFiles = Dir::glob(Config::get('path.routing'), '*.routing.php');
        if (!$routeFiles) return false;
        foreach ($routeFiles as $file) {
            $route = include $file;
            if (!is_array($route)) continue;
            self::$rules = Arr::merge(self::$rules, $route);
        }
    }

    public static function getRules()
    {
        return self::$rules;
    }
}