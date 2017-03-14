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
use FPHP\Store\Facade\Db;

class IndexController extends Controller
{
    public function index()
    {

         // $record = (yield Db::execute('mysql.demo.demo', []));
         // var_dump($record);

        $tmp = (yield Cache::set('demo.redis.cc', 'xxxx', [11, 222]));
        $tmp = (yield Cache::expire('demo.redis.cc', [11, 222], 100));

        yield $this->display('Module/www/index');

    }

}