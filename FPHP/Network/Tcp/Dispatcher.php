<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 16:23
 */

namespace FPHP\Network\Tcp;

use RuntimeException;
use FPHP\Foundation\App;
use FPHP\Util\DesignPattern\Context;
use FPHP\Network\Tcp\Request\Request;

class Dispatcher
{

    public function dispatch(Request $request, Context $context)
    {
        $controllerName = $context->get('controller_name');
        $action = $context->get('action_name');
        $controller = $this->getControllerClass($controllerName);
        if(!class_exists($controller)) {
            throw new RuntimeException("controller:{$controller} not found");
        }

        $controller = new $controller($request, $context);
        if (!is_callable([$controller, $action])) {
            throw new RuntimeException("action:{$action} is not callable in controller:" . get_class($controller));
        }

        if (method_exists($controller,'init')) {
            yield $controller->init();
        }
        yield call_user_func_array([$controller, $action], []);
    }

    private function getControllerClass($controllerName)
    {
        $parts = array_filter(explode('/', $controllerName));
        $controllerName = join('\\', array_map('ucfirst', $parts));
        $app = App::getInstance();
        return $app->getNamespace() . 'Controller\\' .  $controllerName . 'Controller';
    }
}