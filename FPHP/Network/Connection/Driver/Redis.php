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
    private $classHash = null;

    public function heartbeat()
    {

        // 绑定心跳检测事件
        $this->classHash = spl_object_hash($this);
        $this->heartbeatLater();
    }

    public function heartbeatLater()
    {
        Timer::after((int)100, [$this, 'heartbeating'], $this->classHash);
    }

    public function heartbeating()
    {


        $this->pool->getFreeConnection()->remove($this);
        $coroutine = $this->ping();
        Task::execute($coroutine);
    }


    public function ping()
    {
        $this->release();
        $this->heartbeatLater();
    }

    public function closeSocket()
    {
        // TODO: Implement closeSocket() method.
    }
}