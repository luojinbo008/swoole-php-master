<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 17:33
 */

namespace FPHP\Network\Server;

use FPHP\Foundation\App;
use FPHP\Foundation\Container\Di;

class ServerBase
{
    protected $serverStartItems = [

    ];

    protected $workerStartItems = [

    ];

    /**
     * server 启动项
     */
    protected function bootServerStartItem()
    {
        $serverStartItems = array_merge(
            $this->serverStartItems,
            $this->getCustomizedServerStartItems()
        );
        foreach ($serverStartItems as $bootstrap) {
            Di::make($bootstrap)->bootstrap($this);
        }
    }

    /**
     * work 启动项
     * @param $workerId
     */
    protected function bootWorkerStartItem($workerId)
    {
        $workerStartItems = array_merge(
            $this->workerStartItems,
            $this->getCustomizedWorkerStartItems()
        );
        foreach ($workerStartItems as $bootstrap) {
            Di::make($bootstrap)->bootstrap($this, $workerId);
        }
    }

    /**
     * @return array|mixed
     */
    protected function getCustomizedServerStartItems()
    {
        $basePath = App::getInstance()->getBasePath();
        $configFile = $basePath . '/init/ServerStart/config.php';
        if (file_exists($configFile)) {
            return include $configFile;
        } else {
            return [];
        }
    }

    /**
     * @return array|mixed
     */
    protected function getCustomizedWorkerStartItems()
    {
        $basePath = App::getInstance()->getBasePath();
        $configFile = $basePath . '/init/WorkerStart/config.php';
        if (file_exists($configFile)) {
            return include $configFile;
        } else {
            return [];
        }
    }
}