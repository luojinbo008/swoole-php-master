<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/8
 * Time: 11:09
 */

namespace FPHP\Network\Connection\Driver;

use FPHP\Contract\Network\Connection;
use FPHP\Foundation\Coroutine\Task;
use FPHP\Network\Server\Timer\Timer;
use FPHP\Store\Database\Mysql\Exception\MysqlConnectionLostException;
use FPHP\Store\Database\Mysql\Mysql as Engine;

class Mysql extends Base implements Connection
{
    private $classHash = null;

    public function closeSocket()
    {
        return true;
    }

    public function heartbeat()
    {

        // 绑定心跳检测事件
        $this->classHash = spl_object_hash($this);
        $this->heartbeatLater();
    }

    public function heartbeatLater()
    {
        Timer::after($this->config['pool']['heartbeat-time'], [$this, 'heartbeating'], $this->classHash);
    }

    public function heartbeating()
    {
        if (!$this->pool->getFreeConnection()->get($this->classHash)) {
            $this->heartbeatLater();
            return ;
        }

        $this->pool->getFreeConnection()->remove($this);
        $coroutine = $this->ping();
        Task::execute($coroutine);
    }

    public function ping()
    {
        $engine = new Engine($this);
        try {
            $result = (yield $engine->query('select 1'));
        } catch (MysqlConnectionLostException $e){
            return;
        }

        $this->release();
        $this->heartbeatLater();
    }


}