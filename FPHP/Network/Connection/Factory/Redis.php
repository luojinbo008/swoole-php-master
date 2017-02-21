<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:39
 */

namespace FPHP\Network\Connection\Factory;

use FPHP\Contract\Network\ConnectionFactory;
use FPHP\Network\Connection\Driver\Redis as Client;
use FPHP\Store\NoSQL\Redis\RedisClient;

class Redis implements ConnectionFactory
{
    /**
     * @var array
     */
    private $config;
    private $conn;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function create()
    {
        $this->conn = new RedisClient($this->config['host'], $this->config['port']);
        $redis = new Client();
        $redis->setSocket($this->conn);
        $redis->setConfig($this->config);
        return $redis;
    }

    public function close()
    {

    }
}