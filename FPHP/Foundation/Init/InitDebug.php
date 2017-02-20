<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 14:46
 */
namespace FPHP\Foundation\Init;

use FPHP\Contract\Foundation\Initable;
use FPHP\Foundation\App;
use FPHP\Foundation\Core\Config;
use FPHP\Foundation\Core\Debug;

class InitDebug implements Initable
{
    public function bootstrap(App $app)
    {
        Debug::detect();
        Config::set('debug', Debug::get());
    }
}