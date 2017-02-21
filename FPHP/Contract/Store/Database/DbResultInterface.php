<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:21
 */
namespace FPHP\Contract\Store\Database;


interface DbResultInterface
{
    /**
     * DbResultInterface constructor.
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver);

    /**
     * @return int
     */
    public function getLastInsertId();

    /**
     * @return int
     */
    public function getAffectedRows();

    /**
     * @return array
     */
    public function fetchRows();
}