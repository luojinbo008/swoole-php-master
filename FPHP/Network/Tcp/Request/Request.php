<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 16:10
 */

namespace FPHP\Network\Tcp\Request;

use FPHP\Contract\Network\Request as BaseRequest;

class Request implements BaseRequest
{
    private $data;
    private $cmdName;
    private $route;
    private $fd;
    private $fromId;
    private $isHeartBeat = false;

    public function __construct($fd, $fromId, $data)
    {
        // todo 目前支持 json 后溪增加其他数据格式
        $this->fd = $fd;
        $this->fromId = $fromId;
        $data = json_decode($data, true);
        $this->data = isset($data['data']) ? $data['data'] : [];
        $this->cmdName = isset($data['cmdname']) ? $data['cmdname'] : '';
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFd()
    {
        return $this->fd;
    }

    public function getIsHeartBeat()
    {
        return $this->isHeartBeat;
    }

    public function setIsHeartBeat()
    {
        $this->isHeartBeat = true;
    }

    public function getCmdName()
    {
        return $this->cmdName;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }
}