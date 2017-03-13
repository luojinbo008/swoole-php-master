<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:21
 */

namespace FPHP\Contract\Store\NoSQL;

use FPHP\Contract\Network\Connection;
use FPHP\Foundation\Contract\Async;

interface DriverInterface extends Async
{
    public function __construct(Connection $conn);
}