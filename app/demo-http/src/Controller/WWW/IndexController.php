<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/22
 * Time: 10:22
 */
namespace Com\Demo\Src\Controller\WWW;

use FPHP\Foundation\Domain\HttpController as Controller;

use FPHP\Network\Connection\ConnectionManager;

class IndexController extends Controller
{
    public function index()
    {
        try {
            $connection = (yield ConnectionManager::getInstance()->get('redis.default', 0));

            $socket = $connection->getSocket();
            $str = $socket->del("11", "d");
            var_dump($str);
            $connection->release();

        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        yield $this->display('Module/www/index');
    }
}