<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/4/7
 * Time: 17:44
 */
namespace Com\NiuNiu\Src\Module\Model\User;
use FPHP\Store\Facade\Cache;
use FPHP\Store\Facade\Db;

class UserModel
{
    public function getInfo($numid, $areaid) {

        $tmp = Cache::get('redis.user.info', [$areaid, $numid]);
        return  [
            1 => $tmp
        ];
    }
}