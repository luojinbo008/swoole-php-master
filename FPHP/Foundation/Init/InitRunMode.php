<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 16:09
 */

namespace FPHP\Foundation\Init;

use FPHP\Foundation\App;
use FPHP\Foundation\Core\RunMode;
use FPHP\Contract\Foundation\Initable;

class InitRunMode implements Initable
{
    public function bootstrap(App $app)
    {
        RunMode::detect();
    }
}