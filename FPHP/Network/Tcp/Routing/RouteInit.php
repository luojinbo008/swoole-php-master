<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 15:12
 */
namespace FPHP\Network\Tcp\Routing;

use FPHP\Util\DesignPattern\Singleton;

class RouteInit
{
    use Singleton;

    public function init(array $config)
    {
        Router::getInstance()->setConfig($config);
    }
}