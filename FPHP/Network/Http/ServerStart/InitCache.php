<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/24
 * Time: 11:20
 */

namespace FPHP\Network\Http\ServerStart;

use FPHP\Foundation\Core\ConfigLoader;
use FPHP\Foundation\Core\Path;
use FPHP\Store\Facade\Cache;

class InitCache
{
    /**
     * @param $server
     */
    public function bootstrap($server)
    {
        $cacheMap = ConfigLoader::getInstance()->load(Path::getCachePath());
        Cache::init($cacheMap);
    }
}