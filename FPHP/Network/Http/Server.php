<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 14:24
 */
namespace FPHP\Network\Http;
use FPHP\Network\Http\ServerStart\InitExceptionHandlerChain;
use FPHP\Network\Http\ServerStart\InitMiddleware;
use FPHP\Network\Http\ServerStart\InitSqlMap;
use FPHP\Network\Http\ServerStart\InitUrlConfig;
use FPHP\Network\Http\ServerStart\InitRouter;
use FPHP\Network\Http\ServerStart\InitUrlRule;
use FPHP\Network\Server\ServerStart\InitConnectionPool;
use FPHP\Network\Http\ServerStart\InitCache;

use swoole_http_server as SwooleServer;
use swoole_http_request as SwooleHttpRequest;
use swoole_http_response as SwooleHttpResponse;

use FPHP\Network\Server\ServerBase;

class Server extends ServerBase
{
    protected $serverStartItems = [
        InitRouter::class,
        InitUrlRule::class,
        InitUrlConfig::class,
        InitMiddleware::class,
        InitExceptionHandlerChain::class,
        InitCache::class,
        InitSqlMap::class,
    ];

    protected $workerStartItems = [
        InitConnectionPool::class,
    ];

    /**
     * @var SwooleServer
     */
    public $swooleServer;

    public function __construct(SwooleServer $swooleServer, array $config)
    {
        $this->swooleServer = $swooleServer;
        $this->swooleServer->set($config);
    }

    /**
     * 启动 http-server
     */
    public function start()
    {
        $this->swooleServer->on('start', [$this, 'onStart']);
        $this->swooleServer->on('shutdown', [$this, 'onShutdown']);

        $this->swooleServer->on('workerStart', [$this, 'onWorkerStart']);
        $this->swooleServer->on('workerStop', [$this, 'onWorkerStop']);
        $this->swooleServer->on('workerError', [$this, 'onWorkerError']);

        $this->swooleServer->on('request', [$this, 'onRequest']);

        $this->bootServerStartItem();

        $this->swooleServer->start();
    }

    public function stop()
    {

    }

    public function reload()
    {

    }

    public function onStart($swooleServer)
    {
        echo "http server start ......\n";
    }

    public function onShutdown($swooleServer)
    {
        echo "http server shutdown ...... \n";
    }

    public function onWorkerStart($swooleServer, $workerId)
    {
        echo "http worker start ..... \n";
        $this->bootWorkerStartItem($workerId);
    }

    public function onWorkerStop($swooleServer, $workerId)
    {
        echo "http worker stop ..... \n";
    }

    public function onWorkerError($swooleServer, $workerId, $workerPid, $exitCode)
    {
        echo "http worker error ..... \n";
    }

    public function onRequest(SwooleHttpRequest $swooleHttpRequest, SwooleHttpResponse $swooleHttpResponse)
    {
        // @todo  请求数据
        $handler = new RequestHandler();
        $handler->handle($swooleHttpRequest, $swooleHttpResponse);
    }
}