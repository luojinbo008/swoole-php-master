<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:26
 */

namespace FPHP\Network\Http\Routing;

use FPHP\Util\DesignPattern\Singleton;

class RouteInit
{
    use Singleton;

    public function init(array $config)
    {
        Router::getInstance()->setConfig($config);
    }
}