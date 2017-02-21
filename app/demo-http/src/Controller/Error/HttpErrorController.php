<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/20
 * Time: 11:00
 */
namespace Com\Demo\Src\Controller\Error;

use FPHP\Foundation\Domain\HttpController as Controller;

class HttpErrorController extends Controller
{
    public function notFoundPage()
    {
        if ($this->request->isAjax()) {
            yield $this->r(404, "404 Not Found", []);
            return ;
        }
        yield $this->display('Module/Error/404');
    }
}