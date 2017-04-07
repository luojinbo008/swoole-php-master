<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 15:30
 */

namespace FPHP\Network\Tcp\Routing;
use FPHP\Util\DesignPattern\Singleton;

class CmdRuleInit
{
    use Singleton;

    public function init()
    {
        CmdRule::getInstance()->loadRules();
    }
}