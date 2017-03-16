<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/16
 * Time: 14:24
 */

namespace FPHP\Network\WebSocket;

use swoole_websocket_server as SwooleServer;
use FPHP\Contract\Network\Response as BaseResponse;

class Response implements BaseResponse
{

    /**
     * @var SwooleServer
     */
    private $swooleServer;

    private $request;

    public function __construct(SwooleServer $swooleServer, Request $request)
    {
        $this->swooleServer = $swooleServer;
        $this->request = $request;
    }

    public function getSwooleServer()
    {
        return $this->swooleServer;
    }

    public function end($content = '')
    {
        $this->send($content);
    }

    public function sendException($e)
    {
        $serviceName = $this->request->getServiceName();
        $novaServiceName = $this->request->getNovaServiceName();
        $methodName  = $this->request->getMethodName();
        $content = Nova::encodeServiceException($novaServiceName, $methodName, $e);

        $remote = $this->request->getRemote();
        $outputBuffer = '';
        if (nova_encode($serviceName,
            $methodName,
            $remote['ip'],
            $remote['port'],
            $this->request->getSeqNo(),
            $this->request->getAttachData(),
            $content,
            $outputBuffer)) {


            $swooleServer = $this->getSwooleServer();
            $swooleServer->send(
                $this->request->getFd(),
                $outputBuffer
            );
        }
    }

    public function send($content)
    {
        $serviceName = $this->request->getServiceName();
        $novaServiceName = $this->request->getNovaServiceName();
        $methodName  = $this->request->getMethodName();

        $swooleServer = $this->getSwooleServer();
        $sendState = $swooleServer->send(
            $this->request->getFd(),
            $content
        );
    }

}