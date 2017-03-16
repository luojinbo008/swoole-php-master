<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/16
 * Time: 14:27
 */

namespace FPHP\Network\WebSocket;

use FPHP\Contract\Network\Request as BaseRequest;

class Request implements BaseRequest
{
    private $data;
    private $route;
    private $serviceName;
    private $novaServiceName;
    private $methodName;
    private $args;
    private $fd;

    private $remoteIp;
    private $remotePort;
    private $seqNo;
    private $attachData;
    private $isHeartBeat = false;

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

    public function setRemote($ip, $port)
    {
        $this->remoteIp = $ip;
        $this->remotePort = $port;
    }

    public function setSeqNo($seqNo)
    {
        $this->seqNo = $seqNo;
    }

    public function setAttachData($attachData)
    {
        $this->attachData = $attachData;
    }

    public function getAttachData()
    {
        return $this->attachData;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function getNovaServiceName()
    {
        return $this->novaServiceName;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function getRemote()
    {
        return [
            'ip'    => $this->remoteIp,
            'port'  => $this->remotePort,
        ];
    }

    public function getRemotePort()
    {
        return $this->remotePort;
    }

    public function getSeqNo()
    {
        return $this->seqNo;
    }

    public function getIsHeartBeat()
    {
        return $this->isHeartBeat;
    }

    private function formatRoute()
    {
        $serviceName = ucwords($this->serviceName, '.');
        $this->novaServiceName = str_replace('.','\\',$serviceName);

        $path = '/'. str_replace('.', '/', $serviceName) . '/';
        $this->route = $path . $this->methodName;
    }

    private function decodeArgs()
    {
        $this->args = Nova::decodeServiceArgs(
            $this->novaServiceName,
            $this->methodName,
            $this->args
        );
    }

    public function decode()
    {
        $serviceName = $methodName = null;
        $remoteIP = $remotePort = null;
        $seqNo = $novaData = null;
        $attachData = $reqState = null;

        if (nova_decode($this->data, $serviceName, $methodName,
            $remoteIP, $remotePort, $seqNo, $attachData, $novaData)) {

            $this->serviceName = trim($serviceName);
            $this->methodName = trim($methodName);
            $this->args = $novaData;
            $this->remoteIp = $remoteIP;
            $this->remotePort = $remotePort;
            $this->seqNo = $seqNo;
            $this->attachData = $attachData;

            $this->formatRoute();
            $this->decodeArgs();

            if ('com.youzan.service.test' === $serviceName and 'ping' === $methodName) {
                $this->isHeartBeat = true;
            }
        } else {
            //TODO: throw TApplicationException
        }
    }
}