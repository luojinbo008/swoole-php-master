<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/7
 * Time: 17:31
 */

namespace FPHP\Store\Facade;

use FPHP\Store\Database\Flow;

class Db {
    const RETURN_AFFECTED_ROWS  = true;
    const USE_MASTER            = true;
    const RETURN_INSERT_ID      = false;

    public static function execute($sid, $data, $options = [])
    {
        $flow = new Flow();
        yield $flow->query($sid, $data, $options);
        return;
    }

    public static function beginTransaction()
    {
        $flow = new Flow();
        yield $flow->beginTransaction();
    }

    public static function commit()
    {
        $flow = new Flow();
        yield $flow->commit();
    }

    public static function rollback()
    {
        $flow = new Flow();
        yield $flow->rollback();
    }
}