<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 12:48
 */
namespace FPHP\Foundation\Exception\Handler;

use FPHP\Contract\Foundation\ExceptionHandler;

class ExceptionLogger extends BaseExceptionHandler implements ExceptionHandler
{
    public function handle(\Exception $e)
    {
        if (!isset($e->logLevel)) {
            return false;
        }
        if (null === $e->logLevel) {
            return false;
        }

        //TODO: logging
    }
}