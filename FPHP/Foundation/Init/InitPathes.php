<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 16:40
 */

namespace FPHP\Foundation\Init;

use FPHP\Contract\Foundation\Initable;
use FPHP\Foundation\App;
use FPHP\Foundation\Core\Path;

class InitPathes implements Initable
{
    public function bootstrap(App $app)
    {
        $rootPath = $app->getBasePath();
        Path::init($rootPath);
    }
}