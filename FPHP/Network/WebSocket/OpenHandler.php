<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/15
 * Time: 17:35
 */

namespace FPHP\Network\WebSocket;

use FPHP\Foundation\Core\Config;
use FPHP\Foundation\Coroutine\Signal;
use FPHP\Network\Server\Middleware\MiddlewareManager;
use FPHP\Network\Http\Request\Request as HttpRequest;
use FPHP\Foundation\Coroutine\Task;

use FPHP\Network\Server\Timer\Timer;
use FPHP\Util\DesignPattern\Context;
use FPHP\Util\Types\Time;
use swoole_websocket_server as SwooleServer;
use swoole_http_request as SwooleHttpRequest;

class OpenHandler
{
    private $task = null;

    const DEFAULT_TIMEOUT = 30 * 1000;

    public function __construct()
    {
        $this->context = new Context();
        $this->event = $this->context->getEvent();
    }

    public function handle(SwooleServer $server, SwooleHttpRequest $swooleRequest)
    {
        $this->swooleServer = $server;
        $this->fd = $swooleRequest->fd;
        $this->doRequest();
    }

    public function doRequest()
    {
        try {
            $request = new Request($this->fd, []);
            $response = $this->response = new Response($this->swooleServer, $request);

            $this->middleWareManager = new MiddlewareManager($request, $this->context);

            $requestTask = new OpenTask($request, $response, $this->context, $this->middleWareManager);

            $coroutine = $requestTask->run();

            //  bind event
            $this->event->once($this->getRequestFinishJobId(), [$this, 'handleRequestFinish']);
            Timer::after(1, [$this, 'handleTimeout'], $this->getRequestTimeoutJobId());

            $this->task = new Task($coroutine, $this->context);
            $this->task->run();

            } catch (\Exception $e) {

        }
    }

    private function initContext($request, SwooleServer $server)
    {
        $this->context->set('request', $request);
        $this->context->set('server', $server);
    }

    public function handleRequestFinish()
    {
        Timer::clearAfterJob($this->getRequestTimeoutJobId());
        $coroutine = $this->middleWareManager->executeTerminators($this->response);
        Task::execute($coroutine, $this->context);
    }

    public function handleTimeout()
    {
        $this->task->setStatus(Signal::TASK_KILLED);
        $e = new \Exception('server timeout');
        $this->response->sendException($e);
        $this->event->fire($this->getRequestFinishJobId());
    }

    private function getRequestFinishJobId()
    {
        return spl_object_hash($this) . '_request_finish';
    }

    private function getRequestTimeoutJobId()
    {
        return spl_object_hash($this) . '_handle_timeout';
    }
}