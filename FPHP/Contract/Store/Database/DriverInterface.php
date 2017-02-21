<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:21
 */

namespace FPHP\Contract\Store\Database;

use FPHP\Contract\Network\Connection;
use FPHP\Foundation\Contract\Async;

interface DriverInterface extends Async
{
    public function __construct(Connection $conn);

    /**
     * @param $sql
     * @return DbResultInterface
     */
    public function query($sql);

    /**
     * @return DbResultInterface
     */
    public function beginTransaction();

    /**
     * @return DbResultInterface
     */
    public function commit();

    /**
     * @return DbResultInterface
     */
    public function rollback();

    /**
     * @param $link
     * @param $result
     * @return DbResultInterface
     */
    public function onSqlReady($link, $result);
}