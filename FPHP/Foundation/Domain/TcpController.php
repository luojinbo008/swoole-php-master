<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/30
 * Time: 18:41
 */

namespace FPHP\Foundation\Domain;
use FPHP\Contract\Network\Request;
use FPHP\Util\DesignPattern\Context;
use FPHP\Network\Tcp\Response\JsonResponse;

class TcpController extends Controller
{
    public function __construct(Request $request, Context $context)
    {
        parent::__construct($request, $context);
    }

    public function r($code, $msg, $data)
    {
        $data = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        return new JsonResponse($data);
    }
}