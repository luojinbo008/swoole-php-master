<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/10
 * Time: 11:18
 */

namespace FPHP\Store\NoSQL\Redis;

use FPHP\Contract\Store\NoSQL\DriverInterface;
use FPHP\Contract\Network\Connection;
use FPHP\Network\Server\Timer\Timer;

class Redis implements DriverInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var callable
     */
    private $callback;

    private $result;

    const DEFAULT_REDIS_TIMEOUT = 3000;

    public function __construct(Connection $connection)
    {
        $this->setConnection($connection);
    }

    private function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function execute(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __call($method, $arguments)
    {
        $redis = $this->connection->getSocket();
        $config = $this->connection->getConfig();
        $timeout = isset($config['timeout']) ? $config['timeout'] : self::DEFAULT_REDIS_TIMEOUT;
        array_push($arguments, [$this, 'onReceive']);
        Timer::after($timeout, [$this, 'onQueryTimeout'], spl_object_hash($this));
        call_user_func_array([$redis, $method], $arguments);

    }

    public function onReceive($link, $result)
    {
        Timer::clearAfterJob(spl_object_hash($this));
        $exception = null;
        if (false === $result) {
            
        }
        $this->result = $result;
        call_user_func_array($this->callback, [new RedisResult($this), $exception]);
    }

    public function onQueryTimeout()
    {
        $this->connection->close();

        // TODO: sql记入日志
        call_user_func_array($this->callback, [null, new \Exception()]);
    }
}