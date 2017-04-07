<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/24
 * Time: 17:08
 */
namespace FPHP\Network\WebSocket;

use FPHP\Network\Server\Middleware\MiddlewareManager;
use FPHP\Util\DesignPattern\Context;

class OpenTask
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var $response
     */
    private $response;

    /**
     * @var Context
     */
    private $context;
    private $middleWareManager;

    public function __construct(Request $request, Response $response,
                                Context $context, MiddlewareManager $middlewareManager)
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
        if (null !== $response) {
            $this->output($response);
            return;
        }
        
        $result = (yield $dispatcher->dispatch($this->request, $this->context));
        $this->output($result);
        $this->context->getEvent()->fire($this->context->get('request_end_event_name'));
    }

    private function output($execResult)
    {
        return $this->response->end($execResult);
    }
}