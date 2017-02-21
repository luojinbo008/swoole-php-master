<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:37
 */

namespace FPHP\Foundation\Contract;


interface PooledObjectFactory
{
    public function create(); /* PooledObject */

    public function destroy(PooledObject $object);
}