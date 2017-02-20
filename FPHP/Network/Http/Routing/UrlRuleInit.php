<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:30
 */

namespace FPHP\Network\Http\Routing;

use FPHP\Util\DesignPattern\Singleton;

class UrlRuleInit
{
    use Singleton;

    public function init()
    {
        UrlRule::getInstance()->loadRules();
    }
}