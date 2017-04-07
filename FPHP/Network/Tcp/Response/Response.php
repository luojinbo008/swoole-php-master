<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 16:28
 */

namespace FPHP\Network\Tcp\Response;

use swoole_server as SwooleServer;
use FPHP\Contract\Network\Response as BaseResponse;
use FPHP\Network\Tcp\Request\Request;

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

        $swooleServer->send(
            $this->request->getFd(),
            $content
        );
    }
}