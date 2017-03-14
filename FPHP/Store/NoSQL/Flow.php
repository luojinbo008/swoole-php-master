<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/13
 * Time: 10:13
 */
namespace FPHP\Store\NoSQL;

use FPHP\Network\Connection\ConnectionManager;
use FPHP\Contract\Network\Connection;
use RuntimeException;

class Flow
{
    private $engineMap = [
        'Redis' => 'FPHP\Store\NoSQL\Redis\Redis',
    ];

    public function get($sid, $key)
    {
        $connection = (yield $this->getConnectionByConnectionManager($sid));
        $driver = $this->getDriver($connection);
        $result = new ResultFormatter();
        $driver->get($key, [$result, 'response']);
        yield $result;
    }

    public function set($sid, $key, $value, $expire = 0)
    {
        $connection = (yield $this->getConnectionByConnectionManager($sid));
        $driver = $this->getDriver($connection);
        $result = new ResultFormatter();
        $driver->set($key, $value, [$result, 'response']);
        yield $result;
        if ($expire > 0) {
            $ret = (yield $this->expire($sid, $key, 30000));
            if (!$ret) {
                throw new RuntimeException('REDIS EXPIRE TIME ERROR');
            }
            yield 'OK';
        }
    }

    public function expire($sid, $key, $expire = 0)
    {
        $connection = (yield $this->getConnectionByConnectionManager($sid));
        $driver = $this->getDriver($connection);
        $result = new ResultFormatter();
        $driver->expire($key, $expire, [$result, 'response']);
        yield $result;
    }

    private function getConnectionByConnectionManager($sid)
    {
        $connection = (yield ConnectionManager::getInstance()->get($sid));
        if (!($connection instanceof Connection)) {
            throw new GetConnectionException('get connection error redis:' . $sid);
        }
        yield $connection;
    }

    private function getDriver(Connection $connection)
    {
        $engine = $this->parseEngine($connection->getEngine());
        return new $engine($connection);
    }

    private function parseEngine($engine)
    {
        if (!isset($this->engineMap[$engine])) {
            throw new GetConnectionException('can\'t find redis engine : ' . $engine);
        }
        return $this->engineMap[$engine];
    }
}