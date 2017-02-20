<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:29
 */

namespace FPHP\Network\Http\ServerStart;


use FPHP\Network\Http\Routing\UrlRuleInit;


class InitUrlRule
{
    /**
     * @param $server
     */
    public function bootstrap($server)
    {
        UrlRuleInit::getInstance()->init();
    }
}