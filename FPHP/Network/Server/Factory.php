<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 14:41
 */
namespace FPHP\Network\Server;

use RuntimeException;
use FPHP\Foundation\Container\Di;
use FPHP\Foundation\Core\Config;
use FPHP\Network\Http\Server as HttpServer;

use swoole_http_server as SwooleHttpServer;
use swoole_server as SwooleTcpServer;

class Factory
{
    /**
     * @return mixed|object
     */
    public function createHttpServer()
    {
        $config = Config::get('server');
        if (empty($config)) {
            throw new RuntimeException('http server config not found');
        }

        $host = $config['host'];
        $port = $config['port'];
        $config = $config['config'];
        if (empty($host) || empty($port)) {
            throw new RuntimeException('http server config error: empty ip/port');
        }
        $swooleServer = Di::make(SwooleHttpServer::class, [$host, $port], true);
        $server = Di::make(HttpServer::class, [$swooleServer, $config]);
        return $server;
    }
}