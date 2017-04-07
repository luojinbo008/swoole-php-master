<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 15:17
 */

namespace FPHP\Network\Tcp\ServerStart;

use FPHP\Network\Tcp\Routing\RouteInit;
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