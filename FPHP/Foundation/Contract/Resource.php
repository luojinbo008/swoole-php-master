<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 11:07
 */

namespace FPHP\Foundation\Contract;


interface Resource
{
    const AUTO_RELEASE = 1;
    const RELEASE_TO_POOL = 2;
    const RELEASE_AND_DESTROY = 3;

    public function release($strategy = Resource::AUTO_RELEASE);
}