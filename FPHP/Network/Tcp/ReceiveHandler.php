<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 16:05
 */

namespace FPHP\Network\Tcp;

use FPHP\Network\Tcp\Routing\Router;
use \swoole_server as SwooleServer;

use FPHP\Foundation\Core\Config;
use FPHP\Foundation\Core\Debug;
use FPHP\Foundation\Coroutine\Signal;
use FPHP\Foundation\Coroutine\Task;
use FPHP\Network\Server\Middleware\MiddlewareManager;
use FPHP\Network\Server\Timer\Timer;
use FPHP\Util\DesignPattern\Context;
use FPHP\Util\Types\Time;
use FPHP\Network\Tcp\Request\Request;
use FPHP\Network\Tcp\Response\Response;


class ReceiveHandler
{
    private $swooleServer = null;
    private $context = null;
    private $response = null;
    private $fd = null;
    private $fromId = null;
    private $task = null;
    private $middleWareManager = null;

    const DEFAULT_TIMEOUT = 30 * 1000;
    public function __construct()
    {
        $this->context = new Context();
        $this->event = $this->context->getEvent();
    }

    public function handle(SwooleServer $swooleServer, $fd, $fromId, $data)
    {
        $this->swooleServer = $swooleServer;
        $this->fd = $fd;
        $this->fromId = $fromId;

        $this->doReceive($data);
    }

    private function doReceive($data)
    {
        $request = new Request($this->fd, $this->fromId, $data);

        $response = $this->response = new Response($this->swooleServer, $request);
        $this->initContext($request, $response);
        try {
            if ($request->getIsHeartBeat()) {
                $this->swooleServer->send($this->fd, $data);
                return;
            }
            
            $this->middleWareManager = new MiddlewareManager($request, $this->context);
            $receiveTask = new ReceiveTask($request, $response, $this->context, $this->middleWareManager);
            $coroutine = $receiveTask->run();

            //  bind event
            $this->event->once($this->getRequestFinishJobId(), [$this, 'handleRequestFinish']);

            $timeout = $this->context->get('request_timeout');
            Timer::after($timeout, [$this, 'handleTimeout'], $this->getRequestTimeoutJobId());
            $this->task = new Task($coroutine, $this->context);
            $this->task->run();

        } catch(\Exception $e) {
            if (Debug::get()) {
                echo_exception($e);
            }
            $this->response->sendException($e);
            $this->event->fire($this->getRequestFinishJobId());
            return;
        }
    }

    private function initContext($request,  Response $response)
    {
        $this->context->set('request', $request);
        $this->context->set('swoole_response', $response);

        $router = Router::getInstance();
        $route = $router->route($request);

        $this->context->set('controller_name', $route['controller_name']);
        $this->context->set('action_name', $route['action_name']);

        $this->context->set('request_time', Time::stamp());
        $request_timeout = Config::get('server.request_timeout');
        $request_timeout = $request_timeout ? $request_timeout : self::DEFAULT_TIMEOUT;
        $this->context->set('request_timeout', $request_timeout);
        $this->context->set('request_end_event_name', $this->getRequestFinishJobId());
    }

    public function handleRequestFinish()
    {
        Timer::clearAfterJob($this->getRequestTimeoutJobId());
    }

    public function handleTimeout()
    {
        $this->task->setStatus(Signal::TASK_KILLED);
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