<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/22
 * Time: 16:44
 */

namespace FPHP\Store\NoSQL\Redis;
use RuntimeException;

class RedisManager
{

    private $conn = null;

    private $client = null;

    public function __construct($connection)
    {
        $this->conn = $connection;
        $this->client = $connection->getSocket();
    }

    public function get($key)
    {
        $result = new RedisResult();
        $this->client->get($key, [$result, 'response']);
        $this->release();
        yield $result;
    }

    public function expire($key, $expire = 0)
    {
        if ($expire <= 0) {
            yield null;
            return;
        }
        $result = new RedisResult();
        $this->client->EXPIRE($key, $expire, [$result, 'response']);
        $this->release();
        yield $result;
    }

    public function set($key, $value, $expire = 0)
    {
        $result = new RedisResult();
        $this->client->set($key, $value, [$result, 'response']);
        $ret1 = (yield $result);
        if ($expire > 0) {
            $this->client->EXPIRE($key, $expire, [$result, 'response']);
            $ret2 = (yield $result);
            if (!$ret2) {
                throw new RuntimeException('REDIS EXPIRE TIME ERROR');
            }
        }
        $this->release();
        yield $ret1;
    }

    public function release()
    {
        $this->conn->release();
    }

}