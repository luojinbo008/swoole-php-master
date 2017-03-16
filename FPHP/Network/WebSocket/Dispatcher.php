<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/16
 * Time: 18:06
 */

namespace FPHP\Network\WebSocket;

use FPHP\Foundation\App;
use FPHP\Util\DesignPattern\Context;

class Dispatcher {
    private $request = null;
    private $context = null;

    public function dispatch(Request $request, Context $context)
    {
        $this->request = $request;
        $this->context = $context;

        yield $this->runService();
    }

    private function runService()
    {
        $serviceName = $this->getServiceName();

        $service = new $serviceName();
        $method  = $this->request->getMethodName();
        $args    = $this->request->getArgs();
        $args    = is_array($args) ? $args : [$args];

        yield call_user_func_array([$service,$method],$args);
    }

    private function getServiceName()
    {
        return 'niuniu';
    }
}