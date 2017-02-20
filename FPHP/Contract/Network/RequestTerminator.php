<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 11:35
 */

namespace FPHP\Contract\Network;

use FPHP\Util\DesignPattern\Context;

interface RequestTerminator
{
    /**
     * @param Request $request
     * @param Response $response
     * @param Context $context
     * @return void
     */
    public function terminate(Request $request, Response $response, Context $context);
}