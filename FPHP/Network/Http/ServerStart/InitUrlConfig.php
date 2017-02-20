<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:35
 */

namespace FPHP\Network\Http\ServerStart;

use FPHP\Util\Types\URL;
use FPHP\Foundation\Core\Config;

class InitUrlConfig
{
    public function bootstrap($server)
    {
        $config = Config::get('url');
        if (!$config) {
            return;
        }
        URL::setConfig($config);
    }
}