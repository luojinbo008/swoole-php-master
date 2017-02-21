<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:37
 */

namespace FPHP\Network\Connection\Factory;

use FPHP\Contract\Network\ConnectionFactory;

class Mysqli implements ConnectionFactory
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
        $this->conn = new \mysqli();
        $this->conn->connect($this->config['host'], $this->config['user'], $this->config['password'],
            $this->config['database'], $this->config['port']);
        $this->conn->autocommit(true);

        $connection = new \FPHP\Network\Connection\Driver\Mysqli();
        $connection->setSocket($this->conn);
        $connection->setConfig($this->config);
        return $connection;
    }

    public function close()
    {
        mysqli_close($this->conn);
    }

}