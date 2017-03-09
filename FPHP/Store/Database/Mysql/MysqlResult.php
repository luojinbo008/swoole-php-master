<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:25
 */

namespace FPHP\Store\Database\Mysql;

use FPHP\Contract\Store\Database\DbResultInterface;
use FPHP\Contract\Store\Database\DriverInterface;

class MysqlResult implements DbResultInterface
{
    /**
     * @var Mysql
     */
    private $driver;

    /**
     * FutureResult constructor.
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return int
     */
    public function getLastInsertId()
    {
        $insertId = $this->driver->getConnection()->getSocket()->_insert_id;
        yield $this->driver->releaseConnection();
        yield $insertId;
    }

    /**
     * @return int
     */
    public function getAffectedRows()
    {
        $affectedRows = $this->driver->getConnection()->getSocket()->_affected_rows;
        yield $this->driver->releaseConnection();
        yield $affectedRows;
    }

    /**
     * @return array
     */
    public function fetchRows()
    {
        yield $this->driver->releaseConnection();
        yield $this->driver->getResult();
    }
}