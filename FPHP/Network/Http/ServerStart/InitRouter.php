<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:25
 */
namespace FPHP\Network\Http\ServerStart;

use FPHP\Network\Http\Routing\RouteInit;
use FPHP\Foundation\Core\Config;

class InitRouter
{
    /**
     * @param $server
     */
    public function bootstrap($server)
    {
        RouteInit::getInstance()->init(Config::get('route'));
    }
}
