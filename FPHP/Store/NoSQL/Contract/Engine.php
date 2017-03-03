<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:31
 */
namespace FPHP\Store\NoSQL\Contract;

use FPHP\Network\Contract\Connection;

interface Engine
{
    public function __construct(Connection $conn);
}