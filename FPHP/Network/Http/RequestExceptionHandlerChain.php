<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 12:46
 */

namespace FPHP\Network\Http;

use FPHP\Foundation\Exception\ExceptionHandlerChain;
use FPHP\Util\DesignPattern\Singleton;

use FPHP\Network\Http\Exception\Handler\RuntimeHandler;
use FPHP\Network\Http\Exception\Handler\PageNotFoundHandler;

class RequestExceptionHandlerChain extends ExceptionHandlerChain
{
    use Singleton;

    private $handles = [
        PageNotFoundHandler::class,
        RuntimeHandler::class,
    ];

    public function init()
    {
        $this->addHandlersByName($this->handles);
    }
}