<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:29
 */
namespace FPHP\Store\Database;

use FPHP\Store\Database\Contract\Engine;
use FPHP\Network\Contract\Connection;

class Mysql implements Engine {

    private $connection = null;

    public function __construct(Connection $conn)
    {
        $this->connection = $conn;
    }

    public function query($sql, array $config=null)
    {
        try {

        } catch (\Exception $e) {

        }
    }

    public function beginTransaction($autoHandleException=false)
    {

    }

    public function commit()
    {

    }

    public function rollback()
    {

    }

    public function close()
    {
        return release($this->connection);
    }

}