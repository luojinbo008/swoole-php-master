<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 16:20
 */

namespace FPHP\Network\Tcp;

use FPHP\Network\Server\Middleware\MiddlewareManager;
use FPHP\Util\DesignPattern\Context;
use FPHP\Network\Tcp\Request\Request;
use FPHP\Network\Tcp\Response\Response;
use FPHP\Foundation\Container\Di;
class ReceiveTask
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Context
     */
    private $context;
    private $middleWareManager;


    public function __construct(Request $request, Response $response, Context $context, MiddlewareManager $middlewareManager)
    {
        $this->request = $request;
        $this->response = $response;
        $this->context = $context;
        $this->middleWareManager = $middlewareManager;
    }

    public function run()
    {
        try {
            yield $this->doRun();
        } catch (\Exception $e) {
            $this->response->sendException($e);
            $this->context->getEvent()->fire($this->context->get('request_end_event_name'));
        }
    }

    public function doRun()
    {
        $response = (yield $this->middleWareManager->executeFilters());
        if(null !== $response){
            $this->output($response);
            return;
        }
        $dispatcher = Di::make(Dispatcher::class);
        $result = (yield $dispatcher->dispatch($this->request, $this->context));
        yield $this->output($result);
        $this->context->getEvent()->fire($this->context->get('request_end_event_name'));
    }

    private function output($execResult)
    {
        return $this->response->end($execResult);
    }
}