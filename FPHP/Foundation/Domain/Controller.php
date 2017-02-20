<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/20
 * Time: 11:04
 */

namespace FPHP\Foundation\Domain;

use FPHP\Contract\Network\Request;
use FPHP\Util\DesignPattern\Context;

class Controller {

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Context;
     */
    protected $context;

    public function __construct(Request $request, Context $context)
    {
        $this->request = $request;
        $this->context = $context;
    }

}