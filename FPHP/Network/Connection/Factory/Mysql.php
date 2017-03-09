<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/8
 * Time: 11:12
 */

namespace FPHP\Network\Connection\Factory;

use FPHP\Contract\Network\ConnectionFactory;
use RuntimeException;

class Mysql implements ConnectionFactory
{

    private $config;

    private $conn;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function create()
    {
        $this->conn = new \swoole_mysql();
        $serverConfig = [
            'host'      => $this->config['host'],
            'user'      => $this->config['user'],
            'password'  => $this->config['password'],
            'database'  => $this->config['database'],
        ];
        $serverConfig['chatset'] = isset($this->config['chatset']) ? $this->config['chatset'] : 'utf8';

        isset($this->config['port']) && $serverConfig['port'] = $this->config['port'];

        $this->conn->on('close', [$this, 'onClose']);
        $this->conn->connect($this->config, function ($db, $result) {
            if ($result) {
                $db->query('SET AUTOCOMMIT=0', function ($link, $result){
                    if (!$result) {
                        throw new RuntimeException($link->error);
                    }
                });
            } else {
                throw new RuntimeException($db->error);
            }
        });

        $connection = new \FPHP\Network\Connection\Driver\Mysql();
        $connection->setSocket($this->conn);
        $connection->setConfig($this->config);
        return $connection;
    }

    public function close()
    {

    }
}