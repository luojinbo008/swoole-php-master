<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:31
 */
namespace FPHP\Store\Database\Contract;

use FPHP\Network\Contract\Connection;

interface Engine
{
    public function __construct(Connection $conn);

    public function query($sql, array $config = null);

    public function beginTransaction($autoHandleException = false);

    public function commit();

    public function rollback();

    public function close();
}