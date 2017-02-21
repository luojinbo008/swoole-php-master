<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:32
 */

namespace FPHP\Foundation\Contract;


abstract class PooledObject
{
    public function isAlive()
    {
        return true;
    }
}