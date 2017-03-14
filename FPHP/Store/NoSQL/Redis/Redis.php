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
use FPHP\Store\NoSQL\Redis\Exception\RedisCommandException;
use FPHP\Store\NoSQL\Redis\Exception\RedisTimeoutException;

class Redis
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

    public function __call($method, $arguments)
    {
        $this->callback = array_pop($arguments);
        $redis = $this->connection->getSocket();
        $config = $this->connection->getConfig();
        $timeout = isset($config['timeout']) ? $config['timeout'] : self::DEFAULT_REDIS_TIMEOUT;
        array_push($arguments, [$this, 'onReceive']);
        Timer::after($timeout, [$this, 'onTimeout'], spl_object_hash($this));
        call_user_func_array([$redis, $method], $arguments);
    }

    public function onReceive($link, $result)
    {
        Timer::clearAfterJob(spl_object_hash($this));
        $exception = null;
        if (false === $result) {
            $error = $link->errMsg;
            $exception = new RedisCommandException($error);
        } else {
            $this->result = $result;
        }
        $this->connection->release();
        call_user_func($this->callback, $this->result, $exception);
    }

    public function onTimeout()
    {
        $this->connection->close();
        call_user_func($this->callback, null, new RedisTimeoutException());
    }
}