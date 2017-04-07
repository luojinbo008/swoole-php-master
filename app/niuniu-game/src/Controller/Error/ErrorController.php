<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 18:32
 */
namespace Com\NiuNiu\Src\Controller\Error;
use FPHP\Foundation\Domain\TcpController;

class ErrorController extends TcpController
{
    public function notFoundPage()
    {


        yield $this->r(404, "404 Not Found", []);
    }
}