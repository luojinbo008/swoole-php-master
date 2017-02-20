<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:57
 */
namespace FPHP\Foundation\Contract;

interface Async
{
    public function execute(callable $callback);
}