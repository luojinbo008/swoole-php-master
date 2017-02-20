<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 14:27
 */

namespace FPHP\Network\Http\Exception\Handler;

use RuntimeException;
use FPHP\Contract\Foundation\ExceptionHandler;
use FPHP\Foundation\Core\Debug;
use FPHP\Network\Http\Response\JsonResponse;
use FPHP\Network\Http\Response\Response;

class RuntimeHandler implements ExceptionHandler
{
    public function handle(\Exception $e)
    {
        if (!is_a($e, RuntimeException::class)) {
            yield null;
        } else {
            $errMsg = $e->getMessage();
            $code = $e->getCode();
            $request = (yield getContext('request'));

            if ($request->wantsJson()) {
                $context = [
                    'code'  => $code,
                    'msg'   => $e->getMessage(),
                    'data'  => '',
                ];
                yield new JsonResponse($context);
            } else {
                if (Debug::get()) {
                    yield new Response($errMsg);
                } else {

                    //  html
                    $html = '<h1>Work Error!</h1>';
                    yield new Response($html);
                }
            }
        }
    }
}