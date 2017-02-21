<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:36
 */

namespace FPHP\Network\Contract;

use FPHP\Foundation\Core\ObjectPool;

class ConnectionPool extends ObjectPool
{

    public function get() /* Connection */
    {

    }

    public function release(Connection $conn)
    {

    }
}