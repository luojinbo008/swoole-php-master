<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:25
 */
namespace FPHP\Network\Server\ServerStart;

use FPHP\Network\Connection\ConnectionInit;
use FPHP\Contract\Network\Initable;

class InitConnectionPool implements Initable
{
    public function bootstrap($server)
    {
        ConnectionInit::getInstance()->init('connection', $server);
    }
}