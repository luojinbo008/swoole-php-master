<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/7
 * Time: 18:03
 */

namespace FPHP\Network\WebSocket\ServerStart;

use FPHP\Contract\Network\Initable;
use FPHP\Store\Database\Sql\SqlMapInit;

class InitSqlMap implements Initable
{
    /**
     * @param
     */
    public function bootstrap($server)
    {

        SqlMapInit::getInstance()->init();
    }
}