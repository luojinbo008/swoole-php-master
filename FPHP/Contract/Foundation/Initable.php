<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 14:53
 */
namespace FPHP\Contract\Foundation;

use FPHP\Foundation\App;

interface Initable
{
    public function bootstrap(App $app);
}