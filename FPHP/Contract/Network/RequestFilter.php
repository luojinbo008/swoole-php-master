<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 11:33
 */

namespace FPHP\Contract\Network;

use FPHP\Util\DesignPattern\Context;

interface RequestFilter
{
    /**
     * @param Request $request
     * @param Context $context
     * @return \FPHP\Contract\Network\Response
     */
    public function doFilter(Request $request, Context $context);
}