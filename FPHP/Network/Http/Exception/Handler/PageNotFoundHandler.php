<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/20
 * Time: 9:36
 */

namespace FPHP\Network\Http\Exception\Handler;

use FPHP\Contract\Foundation\ExceptionHandler;
use FPHP\Foundation\Core\Config;
use FPHP\Network\Http\Exception\PageNotFoundException;
use FPHP\Network\Http\Response\BaseResponse;
use FPHP\Network\Http\Response\RedirectResponse;

use FPHP\Network\Http\Response\JsonResponse;
use FPHP\Network\Http\Response\Response;

class PageNotFoundHandler implements ExceptionHandler
{
    private $configKey = 'error';

    public function handle(\Exception $e)
    {
        if (!is_a($e, PageNotFoundException::class)) {
            return false;
        } else {
            $config = Config::get($this->configKey, null);
            if (!$config) {
                //  html
                $html = '<h1>404 NotFound!</h1>';
                return new Response($html);
            }

            // 跳转到配置的404页面
            return RedirectResponse::create($config['404'], BaseResponse::HTTP_FOUND);
        }

    }
}