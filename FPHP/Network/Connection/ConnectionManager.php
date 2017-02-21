<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 15:58
 */

namespace FPHP\Network\Connection;

use FPHP\Foundation\Exception\System\InvalidArgumentException;
use FPHP\Util\DesignPattern\Singleton;

class ConnectionManager
{

    use Singleton;

    private static $poolMap = [];

    private static $server;

    public function __construct()
    {

    }

    /**
     * @param $connKey
     * @param int $timeout
     * @return \Generator|void
     * @throws InvalidArgumentException
     */
    public function get($connKey, $timeout = 0)
    {
        if (!isset(self::$poolMap[$connKey])) {
            throw new InvalidArgumentException('No such ConnectionPool:' . $connKey);
        }
        $pool = self::$poolMap[$connKey];
        $connection = $pool->get();
        if ($connection) {
            yield $connection;
            return;
        }
        yield new FutureConnection($this, $connKey, $timeout);
    }

    /**
     * @param $poolKey
     * @param Pool $pool
     */
    public function addPool($poolKey, Pool $pool)
    {
        self::$poolMap[$poolKey] = $pool;
    }

    public function setServer($server) {
        self::$server = $server;
    }
}