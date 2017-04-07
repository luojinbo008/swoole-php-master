<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 14:24
 */
namespace FPHP\Network\WebSocket;

use FPHP\Network\Server\ServerBase;
use FPHP\Network\Http\ServerStart\InitSqlMap;
use FPHP\Network\Server\ServerStart\InitConnectionPool;

use swoole_websocket_server as SwooleServer;
use swoole_http_request as SwooleHttpRequest;
use swoole_http_response as SwooleHttpResponse;


class Server extends ServerBase
{
    protected $serverStartItems = [
        InitSqlMap::class
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

        $this->swooleServer->on('open', [$this, 'onOpen']);
        $this->swooleServer->on('message', [$this, 'onMessage']);
        $this->swooleServer->on('close', [$this, 'onClose']);

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
        echo "webSocket server start ......\n";
    }

    public function onShutdown($swooleServer)
    {
        echo "webSocket server shutdown ...... \n";
    }

    public function onWorkerStart($swooleServer, $workerId)
    {
        echo "webSocket worker start ..... \n";
        $this->bootWorkerStartItem($workerId);
    }

    public function onWorkerStop($swooleServer, $workerId)
    {
        echo "webSocket worker stop ..... \n";
    }

    public function onWorkerError($swooleServer, $workerId, $workerPid, $exitCode)
    {
        echo "webSocket worker error ..... \n";
    }

    public function onOpen(SwooleServer $server, SwooleHttpRequest $request)
    {
        echo "open ..... \n";
        (new OpenHandler())->handle($server, $request);
    }

    public function onMessage(SwooleServer $server, SwooleWebSocketFrame $frame)
    {

    }

    public function onClose(SwooleServer $server, $fd)
    {

    }
}