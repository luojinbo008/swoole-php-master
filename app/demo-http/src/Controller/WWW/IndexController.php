<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/22
 * Time: 10:22
 */
namespace Com\Demo\Src\Controller\WWW;

use FPHP\Foundation\Domain\HttpController as Controller;
use FPHP\Store\Facade\Cache;

class IndexController extends Controller
{
    public function index()
    {
        yield Cache::set('demo.redis.cc', "xx2222222211", [11, 222]);
        $tmp = (yield Cache::get('demo.redis.cc', [11, 222]));
        var_dump($tmp);

        yield $this->display('Module/www/index');
    }

}