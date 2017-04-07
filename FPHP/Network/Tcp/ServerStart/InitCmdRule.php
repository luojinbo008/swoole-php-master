<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 15:28
 */

namespace FPHP\Network\Tcp\ServerStart;

use FPHP\Network\Tcp\Routing\CmdRuleInit;

class InitCmdRule
{
    /**
     * @param $server
     */
    public function bootstrap($server)
    {
        CmdRuleInit::getInstance()->init();
    }
}