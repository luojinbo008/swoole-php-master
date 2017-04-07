<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 15:13
 */

namespace FPHP\Network\Tcp\Routing;

use FPHP\Util\DesignPattern\Singleton;
use FPHP\Network\Tcp\Request\Request;

class Router
{
    use Singleton;
    private $config;
    private $route;
    private $cmdName;
    private $rules;
    private $separator = "/";

    public function setConfig($config)
    {
        $this->config = $config;
    }

    private function prepare($cmdName)
    {
        $this->rules = CmdRule::getRules();
        if (empty($cmdName)) {
            return;
        }
        $this->cmdName = $cmdName;
    }


    public function route(Request $request)
    {
        $cmdName= $request->getCmdName();
        $this->prepare($cmdName);
        $this->parseRegexeCmd();
        $this->repairRoute();
        $request->setRoute($this->route);
        $route = $this->parseRoute();
        $this->clear();
        return $route;
    }

    private function clear()
    {
        $this->cmdName = '';
        $this->route = '';
    }

    public function parseRoute()
    {
        $parts = array_filter(explode($this->separator, trim($this->route, $this->separator)));
        $route['action_name'] = array_pop($parts);
        $route['controller_name'] = join('/', $parts);
        return $route;
    }

    private function repairRoute()
    {
        $path = array_filter(explode($this->separator, $this->route));
        $pathCount = count($path);
        switch ($pathCount) {
            case 0:
                $this->setDefaultRoute();
                break;
            case 1:
                $this->setDefaultControllerAndDefaultAction();
                break;
            case 2:
                $this->setDefaultAction();
                break;
        }
    }

    private function parseRegexeCmd()
    {
        $result = CmdRegex::decode($this->cmdName, $this->rules);
        if (!$result) {
            $this->setDefaultCmdName();
            $result = CmdRegex::decode($this->cmdName, $this->rules);
        }
        $this->route = $result['route'];
    }


    private function setDefaultControllerAndDefaultAction()
    {
        $path = array_filter(explode($this->separator, $this->route));
        array_push($path, $this->getDefaultController(), $this->getDefaultAction());
        $this->route = join($this->separator, $path);
    }

    private function setDefaultAction()
    {
        $path = array_filter(explode($this->separator, $this->route));
        array_push($path, $this->getDefaultAction());
        $this->route = join($this->separator, $path);
    }

    private function getDefaultAction()
    {
        return $this->config['default_action'];
    }

    private function getDefaultController()
    {
        return $this->config['default_controller'];
    }

    private function setDefaultCmdName()
    {
        $this->cmdName = $this->getDefaultCmdName();
    }

    private function getDefaultCmdName()
    {
        return $this->config['default_cmd'];
    }
}