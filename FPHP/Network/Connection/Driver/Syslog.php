<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:08
 */

namespace FPHP\Network\Connection\Driver;

use FPHP\Contract\Network\Connection;

class Syslog extends Base implements Connection
{
    public function closeSocket()
    {
        // TODO: Implement closeSocket() method.
    }
}