<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:45
 */
namespace FPHP\Network\Connection\Driver;

use FPHP\Contract\Network\Connection;
use FPHP\Foundation\Coroutine\Task;
use FPHP\Network\Server\Timer\Timer;
use FPHP\Store\NoSQL\Redis\Redis as Engine;

class Redis extends Base implements Connection
{
    private $classHash = null;

    public function closeSocket()
    {
        return true;
    }

}