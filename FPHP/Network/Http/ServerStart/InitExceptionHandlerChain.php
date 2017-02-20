<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 14:24
 */

namespace FPHP\Network\Http\ServerStart;

use FPHP\Network\Http\RequestExceptionHandlerChain;

class InitExceptionHandlerChain
{
    /**
     * @param \FPHP\Network\Http\Server $server
     */
    public function bootstrap($server)
    {
        RequestExceptionHandlerChain::getInstance()->init();
    }
}