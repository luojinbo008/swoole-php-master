<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/28
 * Time: 16:00
 */
namespace FPHP\Network\Tcp;

use FPHP\Network\Server\WorkerStart\InitConnectionPool;
use FPHP\Network\Server\ServerBase;
use FPHP\Network\Tcp\ServerStart\InitCmdRule;
use FPHP\Network\Tcp\ServerStart\InitSqlMap;
use FPHP\Network\Tcp\ServerStart\InitRouter;
use swoole_server as SwooleServer;

class Server extends ServerBase
{
    protected $serverStartItems = [
        InitRouter::class,
        InitCmdRule::class,
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

    public function start()
    {
        $this->swooleServer->on('start', [$this, 'onStart']);
        $this->swooleServer->on('shutdown', [$this, 'onShutdown']);

        $this->swooleServer->on('workerStart', [$this, 'onWorkerStart']);
        $this->swooleServer->on('workerStop', [$this, 'onWorkerStop']);
        $this->swooleServer->on('workerError', [$this, 'onWorkerError']);

        $this->swooleServer->on('connect', [$this, 'onConnect']);
        $this->swooleServer->on('receive', [$this, 'onReceive']);
        $this->swooleServer->on('close', [$this, 'onClose']);

        $this->bootServerStartItem();
        $this->swooleServer->start();
    }

    public function onConnect()
    {
        echo "connecting ......\n";
    }

    public function onClose()
    {
        echo "closing .....\n";
    }

    public function onStart($swooleServer)
    {
        echo "server start .....\n";
    }

    public function onShutdown($swooleServer)
    {
        echo "server shutdown .....\n";
    }

    public function onWorkerStart($swooleServer, $workerId)
    {
        $this->bootWorkerStartItem($workerId);
        echo "worker starting .....\n";
    }

    public function onWorkerStop($swooleServer, $workerId)
    {
        echo "worker stoping ....\n";
    }

    public function onWorkerError($swooleServer, $workerId, $workerPid, $exitCode)
    {
        echo "worker error happening ....\n";
    }

    public function onPacket(SwooleServer $swooleServer, $data, array $clientInfo)
    {
        echo "receive packet data\n\n\n\n";
    }

    public function onReceive(SwooleServer $swooleServer, $fd, $fromId, $data)
    {
        (new ReceiveHandler())->handle($swooleServer, $fd, $fromId, $data);
    }
}
