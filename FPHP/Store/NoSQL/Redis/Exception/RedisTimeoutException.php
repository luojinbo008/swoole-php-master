<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/14
 * Time: 16:32
 */
namespace FPHP\Store\NoSQL\Redis\Exception;

use FPHP\Network\Connection\Exception\ConnectionLostException;

class RedisTimeoutException extends ConnectionLostException
{

}