<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 13:32
 */

namespace FPHP\Network\Http;

use RuntimeException;
use FPHP\Foundation\App;
use FPHP\Network\Http\Request\Request;
use FPHP\Util\DesignPattern\Context;
use FPHP\Network\Http\Exception\PageNotFoundException;

class Dispatcher
{
    public function dispatch(Request $request, Context $context)
    {
        $controllerName = $context->get('controller_name');
        $action = $context->get('action_name');

        $controller = $this->getControllerClass($controllerName);

        if(!class_exists($controller)) {
            throw new PageNotFoundException("controller:{$controller} not found");
        }

        $controller = new $controller($request, $context);
        if(!is_callable([$controller, $action])) {
            throw new PageNotFoundException("action:{$action} is not callable in controller:" . get_class($controller));
        }

        if (method_exists($controller,'init')) {
            yield $controller->init();
        }
        yield $controller->$action();
    }

    private function getControllerClass($controllerName)
    {
        $parts = array_filter(explode('/', $controllerName));
        $controllerName = join('\\', array_map('ucfirst', $parts));
        $app = App::getInstance();
        return $app->getNamespace() . 'Controller\\' .  $controllerName . 'Controller';
    }
}