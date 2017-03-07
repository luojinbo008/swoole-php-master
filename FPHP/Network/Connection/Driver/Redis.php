<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:45
 */
namespace FPHP\Network\Connection\Driver;

use FPHP\Contract\Network\Connection;

class Redis extends Base implements Connection
{
    public function closeSocket()
    {
        return true;
    }
}