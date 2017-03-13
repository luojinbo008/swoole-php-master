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
    const CONNECTION_CONTEXT = 'connection_context';
    const CONNECTION_STACK = 'connection_stack';

    private $engineMap = [
        'Redis' => 'FPHP\Store\NoSQL\Redis\Redis',
    ];

    public function get($sid, $key)
    {
        $connection = (yield $this->getConnectionByConnectionManager($sid));

        $driver = $this->getDriver($connection);

        $connectionStack = (yield getContext(self::CONNECTION_STACK, null));
        if (null !== $connectionStack && $connectionStack instanceof \SplStack) {
            $connectionStack->push($connection);
            yield setContext(self::CONNECTION_STACK, $connectionStack);
            return;
        }

        yield $driver->get($key);
    }

    public function set($sid, $value, $key, $expire = 0)
    {
        $connection = (yield $this->getConnectionByConnectionManager($sid));
        $driver = $this->getDriver($connection);
        yield $driver->set($key, $value);

        if ($expire > 0) {
            $ret = (yield $this->expire($sid, $key, $expire));
            if (!$ret) {
                throw new RuntimeException('REDIS EXPIRE TIME ERROR');
            }
        }
    }

    public function expire($sid, $key, $expire = 0)
    {
        $connection = (yield $this->getConnectionByConnectionManager($sid));
        $driver = $this->getDriver($connection);
        yield $driver->expire($key, $expire);
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