<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 11:31
 */

namespace FPHP\Network\Server\Middleware;


use FPHP\Contract\Network\Request;
use FPHP\Contract\Network\RequestFilter;
use FPHP\Contract\Network\RequestTerminator;
use FPHP\Util\DesignPattern\Context;

class MiddlewareManager
{
    private $middlewareConfig;
    private $request;
    private $context;
    private $middlewares = [];

    public function __construct(Request $request, Context $context)
    {
        $this->middlewareConfig = MiddlewareConfig::getInstance();
        $this->request = $request;
        $this->context = $context;

        $this->initMiddlewares();
    }

    public function executeFilters()
    {
        $middlewares = $this->middlewares;
        foreach ($middlewares as $middleware) {
            if (!$middleware instanceof RequestFilter) {
                continue;
            }

            $response = (yield $middleware->doFilter($this->request, $this->context));
            if (null !== $response) {
                yield $response;
                return;
            }
        }
    }

    public function executeTerminators($response)
    {
        $middlewares = $this->middlewares;
        foreach ($middlewares as $middleware) {
            if (!$middleware instanceof RequestTerminator) {
                continue;
            }
            yield $middleware->terminate($this->request, $response, $this->context);
        }
    }

    private function initMiddlewares()
    {
        $middlewares = [];
        $groupValues = $this->middlewareConfig->getGroupValue($this->request);
        $groupValues = $this->middlewareConfig->addBaseFilters($groupValues);
        $groupValues = $this->middlewareConfig->addBaseTerminators($groupValues);
        foreach ($groupValues as $groupValue) {
            $objectName = $this->getObject($groupValue);
            $obj = new $objectName();
            $middlewares[$objectName] = $obj;
        }
        $this->middlewares = $middlewares;
    }

    private function getObject($objectName)
    {
        return $objectName;
    }
}