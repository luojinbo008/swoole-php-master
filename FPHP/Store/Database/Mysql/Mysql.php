<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:18
 */
namespace FPHP\Store\Database\Mysql;

use FPHP\Contract\Network\Connection;
use FPHP\Contract\Store\Database\DbResultInterface;
use FPHP\Contract\Store\Database\DriverInterface;
use FPHP\Network\Server\Timer\Timer;
use FPHP\Store\Database\Mysql\Exception\MysqlConnectionLostException;
use FPHP\Store\Database\Mysql\Exception\MysqlQueryException;
use FPHP\Store\Database\Mysql\Exception\MysqlQueryTimeoutException;
use FPHP\Store\Database\Mysql\Exception\MysqlSqlSyntaxException;
use FPHP\Store\Database\Mysql\Exception\MysqlTransactionException;

class Mysql implements DriverInterface
{
    /**
     * @var Connection
     */
    private $connection;

    private $sql;

    /**
     * @var callable
     */
    private $callback;

    private $result;

    const DEFAULT_QUERY_TIMEOUT = 3000;

    public function __construct(Connection $connection)
    {
        $this->setConnection($connection);
    }

    private function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function execute(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param $sql
     * @return DbResultInterface
     */
    public function query($sql)
    {
        $config = $this->connection->getConfig();

        $timeout = isset($config['timeout']) ? $config['timeout'] : self::DEFAULT_QUERY_TIMEOUT;
        $this->sql = $sql;

        $this->connection->getSocket()->query($this->sql, [$this, 'onSqlReady']);

        Timer::after($timeout, [$this, 'onQueryTimeout'], spl_object_hash($this));
        yield $this;
    }

    /**
     * @param $link
     * @param $result
     * @return DbResultInterface
     */
    public function onSqlReady($link, $result)
    {
        Timer::clearAfterJob(spl_object_hash($this));
        $exception = null;
        if ($result === false) {
            if (in_array($link->_errno, [2013, 2006])) {
                $this->connection->close();
                $exception = new MysqlConnectionLostException();
            } elseif ($link->_errno == 1064) {
                $error = $link->_error;
                $this->connection->release();
                $exception = new MysqlSqlSyntaxException($error);
            } else {
                $error = $link->_error;
                $this->connection->release();
                $exception = new MysqlQueryException($error);
            }
        }
        $this->result = $result;
        call_user_func_array($this->callback, [new MysqlResult($this), $exception]);
    }

    public function onQueryTimeout()
    {
        $this->connection->close();

        // TODO: sql记入日志
        call_user_func_array($this->callback, [null, new MysqlQueryTimeoutException()]);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function beginTransaction()
    {
        $beginTransaction = (yield $this->connection->getSocket()->query('BEGIN', function ($link, $result){
            if (!$result) {
                throw new MysqlTransactionException('mysql begin transaction error');
            }
        }));
        yield $beginTransaction;
    }

    public function commit()
    {
        $commit = (yield $this->connection->getSocket()->query('COMMIT', function ($link, $result){
            if (!$result) {
                throw new MysqlTransactionException('mysql commit error');
            }
        }));
        $this->connection->release();
        yield $commit;
    }

    public function rollback()
    {
        $rollback = (yield $this->connection->getSocket()->query('ROLLBACK', function ($link, $result){
            if (!$result) {
                throw new MysqlTransactionException('mysql rollback error');
            }
        }));
        $this->connection->release();
        yield $rollback;
    }

    public function releaseConnection()
    {
        $beginTransaction = (yield getContext('begin_transaction', false));
        if ($beginTransaction === false) {
            $this->connection->release();
        }
        yield true;
    }
}
