<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/22
 * Time: 16:44
 */

namespace FPHP\Store\NoSQL;

use FPHP\Foundation\Contract\Async;
use FPHP\Foundation\Exception\FPHPException;

class ResultFormatter implements Async
{

    private $callback = null;

    public function execute(callable $callback)
    {
        $this->callback = $callback;
    }

    public function response($data, $status)
    {
        call_user_func($this->callback, $data);
    }
}