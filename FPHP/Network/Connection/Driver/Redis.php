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

class Redis extends Base implements Connection
{

    public function heartbeat()
    {
        $this->heartbeatLater();
    }

    public function heartbeatLater()
    {
        // 回收 连接池
        Timer::tick((int)$this->config['pool']['keeping-sleep-time'],
            [$this, 'sleepkeeping'], spl_object_hash($this));
    }

    public function sleepkeeping()
    {
        if (!$this->pool->getActiveConnection()->get(spl_object_hash($this))) {
            return ;
        }
        $this->pool->getActiveConnection()->remove($this);
    }

    public function closeSocket()
    {
        return true;
    }
}