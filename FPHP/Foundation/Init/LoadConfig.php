<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 16:54
 */

namespace FPHP\Foundation\Init;
use FPHP\Foundation\App;
use FPHP\Foundation\Core\Config;

class LoadConfig
{
    /**
     * Bootstrap the given application.
     *
     * @param  \FPHP\Foundation\App  $app
     */
    public function bootstrap(App $app)
    {
        date_default_timezone_set('Asia/Shanghai');
        mb_internal_encoding('UTF-8');
        Config::init();
    }
}