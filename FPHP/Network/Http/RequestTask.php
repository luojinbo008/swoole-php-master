<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 12:43
 */

namespace FPHP\Network\Http;

use FPHP\Contract\Network\Request;
use FPHP\Network\Http\Response\BaseResponse;
use FPHP\Network\Http\Response\InternalErrorResponse;
use FPHP\Network\Server\Middleware\MiddlewareManager;
use FPHP\Util\DesignPattern\Context;
use FPHP\Foundation\Container\Di;
use FPHP\Foundation\Coroutine\Task;

use swoole_http_response as SwooleHttpResponse;

class RequestTask
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var SwooleHttpResponse
     */
    private $swooleResponse;
    /**
     * @var Context
     */
    private $context;

    private $middleWareManager;

    public function __construct(Request $request, SwooleHttpResponse $swooleResponse,
                                Context $context, MiddlewareManager $middlewareManager)
    {
        $this->request = $request;
        $this->swooleResponse = $swooleResponse;
        $this->context = $context;
        $this->middleWareManager = $middlewareManager;
    }

    public function run()
    {
        try{
            yield $this->doRun();
        } catch (\Exception $e) {
            $coroutine = RequestExceptionHandlerChain::getInstance()->handle($e);
            Task::execute($coroutine, $this->context);
            $this->context->getEvent()->fire($this->context->get('request_end_event_name'));
        }
    }

    public function doRun()
    {
        $response = (yield $this->middleWareManager->executeFilters());
        if(null !== $response){
            $this->context->set('response', $response);
            yield $response->sendBy($this->swooleResponse);
            return;
        }

        $Dispatcher = Di::make(Dispatcher::class);
        $response = (yield $Dispatcher->dispatch($this->request, $this->context));
        if (null === $response) {
            $response = new InternalErrorResponse('网络错误', BaseResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->context->set('response', $response);
        yield $response->sendBy($this->swooleResponse);

        $this->context->getEvent()->fire($this->context->get('request_end_event_name'));
    }
}