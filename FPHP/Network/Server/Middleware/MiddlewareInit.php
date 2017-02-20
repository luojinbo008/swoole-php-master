<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 12:14
 */

namespace FPHP\Network\Server\Middleware;


namespace FPHP\Network\Server\Middleware;

use FPHP\Util\DesignPattern\Singleton;

class MiddlewareInit
{
    use Singleton;

    public function initConfig(array $config = [])
    {
        $config['match'] = isset($config['match']) ? $config['match'] : [];
        MiddlewareConfig::getInstance()->setConfig($config);
    }

    public function initExtendFilters(array $extendFilters = [])
    {
        MiddlewareConfig::getInstance()->setExtendFilters($extendFilters);
    }

    public function initExtendTerminators(array $extendTerminators = [])
    {
        MiddlewareConfig::getInstance()->setExtendTerminators($extendTerminators);
    }
}