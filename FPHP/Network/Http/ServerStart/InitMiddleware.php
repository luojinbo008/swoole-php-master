<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 11:24
 */

namespace FPHP\Network\Http\ServerStart;

use FPHP\Network\Server\Middleware\MiddlewareInit;
use FPHP\Foundation\Core\Config;
use FPHP\Foundation\Core\ConfigLoader;

class InitMiddleware
{
    private $extendFilters = [
        // 'filter1', 'filter2'
    ];

    private $extendTerminators = [
        // 'terminator1', 'terminator2'
    ];

    /**
     * @param $server
     */
    public function bootstrap($server)
    {
        $middlewareInit = MiddlewareInit::getInstance();
        $middlewareConfig = ConfigLoader::getInstance()->load(Config::get('path.middleware'));
        $middlewareConfig = isset($middlewareConfig['middleware']) ? $middlewareConfig['middleware'] : [];
        $middlewareConfig = !is_array($middlewareConfig) || [] == $middlewareConfig ? [] : $middlewareConfig;
        $middlewareInit->initConfig($middlewareConfig);
        $middlewareInit->initExtendFilters($this->extendFilters);
        $middlewareInit->initExtendTerminators($this->extendTerminators);
    }
}