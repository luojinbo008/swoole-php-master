<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/3
 * Time: 18:31
 */

use FPHP\Store\NoSQL\Contract\Engine;

use FPHP\Network\Contract\Connection;

class Redis implements Engine
{
    private $connection = null;

    public function __construct(Connection $conn)
    {
        $this->connection = $conn;
    }

}