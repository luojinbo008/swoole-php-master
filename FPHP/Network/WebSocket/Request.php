<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/24
 * Time: 17:00
 */

namespace FPHP\Network\WebSocket;

use FPHP\Contract\Network\Request as BaseRequest;

class Request implements BaseRequest
{
    private $data;
    private $route;
    private $fd;

    public function __construct($fd, $data)
    {
        $this->fd = $fd;
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setFd($fd)
    {
        $this->fd = $fd;
    }

    public function getFd()
    {
        return $this->fd;
    }

    public function getRoute()
    {
        // TODO: Implement getRoute() method.

    }
}