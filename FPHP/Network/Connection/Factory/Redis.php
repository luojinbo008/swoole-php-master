<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:39
 */

namespace FPHP\Network\Connection\Factory;

use FPHP\Contract\Network\ConnectionFactory;
use RuntimeException;

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
        $this->conn = new \swoole_redis();
        $this->conn->on('close', [$this, 'close']);
        $this->conn->connect($this->config['host'], $this->config['port'],  function ($redis, $result) {
            if (!$result) {
                throw new RuntimeException($redis->errMsg);
            }
        });

        $connection = new \FPHP\Network\Connection\Driver\Redis();
        $connection->setSocket($this->conn);
        $connection->setConfig($this->config);
        return $connection;
    }

    public function close()
    {

    }
}