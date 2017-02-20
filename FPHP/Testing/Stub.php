<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 11:09
 */

namespace FPHP\Testing;

abstract class Stub
{
    protected $realClassName = null;

    /**
     * @return null
     */
    public function getRealClassName()
    {
        return $this->realClassName;
    }

}