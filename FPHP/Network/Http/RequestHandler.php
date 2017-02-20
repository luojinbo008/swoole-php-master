<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/15
 * Time: 17:08
 */

namespace FPHP\Network\Http;

use FPHP\Util\Types\Time;
use swoole_http_request as SwooleHttpRequest;
use swoole_http_response as SwooleHttpResponse;

use FPHP\Network\Http\Request\Request;
use FPHP\Util\DesignPattern\Context;
use FPHP\Foundation\Core\Config;
use FPHP\Foundation\Core\Debug;
use FPHP\Network\Http\Routing\Router;
use FPHP\Foundation\Coroutine\Signal;
use FPHP\Foundation\Coroutine\Task;
use FPHP\Network\Server\Middleware\MiddlewareManager;
use FPHP\Network\Server\Timer\Timer;
use FPHP\Network\Http\Response\InternalErrorResponse;
use FPHP\Network\Http\Response\BaseResponse;

class RequestHandler
{
    private $context = null;
    private $middleWareManager = null;
    private $task = null;
    private $event = null;

    const DEFAULT_TIMEOUT = 1 * 1000;

    public function __construct()
    {
        $this->context = new Context();
        $this->event = $this->context->getEvent();
    }

    public function handle(SwooleHttpRequest $swooleRequest, SwooleHttpResponse $swooleResponse)
    {
        try {
            $request = Request::createFromSwooleHttpRequest($swooleRequest);
            $this->initContext($request, $swooleResponse);
            $this->middleWareManager = new MiddlewareManager($request, $this->context);

            $requestTask = new RequestTask($request, $swooleResponse, $this->context, $this->middleWareManager);
            $coroutine = $requestTask->run();

            //  bind event
            $timeout = $this->context->get('request_timeout');
            $this->event->once($this->getRequestFinishJobId(), [$this, 'handleRequestFinish']);

            Timer::after($timeout, [$this, 'handleTimeout'], $this->getRequestTimeoutJobId());

            $this->task = new Task($coroutine, $this->context);
            $this->task->run();
        } catch (\Exception $e) {
            if (Debug::get()) {
                echo_exception($e);
            }
            $coroutine = RequestExceptionHandlerChain::getInstance()->handle($e);

            Task::execute($coroutine, $this->context);
            $this->event->fire($this->getRequestFinishJobId());
        }
    }

    private function initContext($request, SwooleHttpResponse $swooleResponse)
    {
        $this->context->set('request', $request);
        $this->context->set('swoole_response', $swooleResponse);

        $router = Router::getInstance();
        $route = $router->route($request);

        $this->context->set('controller_name', $route['controller_name']);
        $this->context->set('action_name', $route['action_name']);

        $cookie = new Cookie($request, $swooleResponse);
        $this->context->set('cookie', $cookie);

        $this->context->set('request_time', Time::stamp());
        $request_timeout = Config::get('server.request_timeout');
        $request_timeout = $request_timeout ? $request_timeout : self::DEFAULT_TIMEOUT;
        $this->context->set('request_timeout', $request_timeout);
        $this->context->set('request_end_event_name', $this->getRequestFinishJobId());
    }

    public function handleRequestFinish()
    {
        Timer::clearAfterJob($this->getRequestTimeoutJobId());
        $response = $this->context->get('response');
        $coroutine = $this->middleWareManager->executeTerminators($response);
        Task::execute($coroutine, $this->context);
    }

    public function handleTimeout()
    {
        echo 123;
        $this->task->setStatus(Signal::TASK_KILLED);
        $response = new InternalErrorResponse('服务器超时', BaseResponse::HTTP_GATEWAY_TIMEOUT);
        $this->context->set('response', $response);
        $swooleResponse = $this->context->get('swoole_response');

        $response->sendBy($swooleResponse);

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