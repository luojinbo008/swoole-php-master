<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/24
 * Time: 17:05
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

    }

    public function send($content)
    {
        $swooleServer = $this->getSwooleServer();
        $outputBuffer = '';
        $sendState = $swooleServer->push(
            $this->request->getFd(),
            $content
        );
    }

}