<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 15:58
 */

namespace FPHP\Foundation\Init;


use FPHP\Contract\Foundation\Initable;
use FPHP\Foundation\App;

class InitEnv implements Initable
{
    public function bootstrap(App $app)
    {
        ini_set('memory_limit', '1024M');
    }
}