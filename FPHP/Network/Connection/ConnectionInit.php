<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:28
 */
namespace FPHP\Network\Connection;


use FPHP\Foundation\Core\Config;
use FPHP\Util\DesignPattern\Singleton;
use FPHP\Network\Connection\Factory\Http;
use FPHP\Network\Connection\Factory\Mysqli;
use FPHP\Network\Connection\Factory\Redis;
use FPHP\Network\Connection\Factory\Syslog;

class ConnectionInit
{
    use Singleton;

    private $engineMap = [
        'mysqli',
        'http',
        'redis',
        'syslog',
    ];

    public $directory = '';

    public $poolName='';

    public function __construct()
    {

    }

    /**
     * @param $directory
     */
    public function init($directory, $server)
    {
        if (!empty($directory)) {
            $this->directory = $directory;
            $config = Config::get($this->directory);
            $this->initConfig($config);
        }
        $connectionManager = ConnectionManager::getInstance();
        $connectionManager->setServer($server);
    }

    private function initConfig($config)
    {
        if (!is_array($config)) {
            return;
        }
        foreach ($config as $k => $cf) {
            if (!isset($cf['engine'])) {
                $this->poolName = '' === $this->poolName ? $k : $this->poolName . '.' . $k;
                $this->initConfig($cf);
                continue;
            }
            if (!isset($cf['pool']) || empty($cf['pool'])) {
                $this->poolName = '';
                continue;
            }

            // 创建连接池
            $dir = $this->poolName;
            $this->poolName = '' === $this->poolName ? $k : $this->poolName . '.' . $k;
            $factoryType = $cf['engine'];
            if (in_array($factoryType, $this->engineMap)) {
                $factoryType = ucfirst($factoryType);
                $cf['pool']['pool_name'] = $this->poolName;
                $this->initPool($factoryType, $cf);
                $fileConfigKeys = array_keys($config);
                $endKey = end($fileConfigKeys);
                $this->poolName = $k == $endKey ? '' : $dir;
            }
        }
    }

    /**
     * @param $factoryType
     * @param $config
     */
    private function initPool($factoryType, $config)
    {
        switch ($factoryType) {
            case 'Redis':
                $factory = new Redis($config);
                break;
            case 'Syslog':
                $factory = new Syslog($config);
                break;
            case 'Http':
                $factory = new Http($config);
                break;
            case 'Mysqli':
                $factory = new Mysqli($config);
                break;
            default:
                break;
        }
        $connectionPool = new Pool($factory, $config, $factoryType);
        ConnectionManager::getInstance()->addPool($config['pool']['pool_name'], $connectionPool);
    }
}
