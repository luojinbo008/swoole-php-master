<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:09
 */

namespace FPHP\Network\Connection\Driver;

use FPHP\Contract\Network\Connection;
use FPHP\Foundation\Coroutine\Task;
use FPHP\Network\Server\Timer\Timer;
use FPHP\Store\Database\Mysql\Exception\MysqliConnectionLostException;
use FPHP\Store\Database\Mysql\Mysqli as Engine;

class Mysqli extends Base implements Connection
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
        Timer::after($this->config['pool']['heartbeat-time'], [$this,'heartbeating'], $this->classHash);
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
        try{
            $result = (yield $engine->query('select 1'));
        } catch (MysqliConnectionLostException $e){
            return;
        }

        $this->release();
        $this->heartbeatLater();
    }


}