<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:50
 */

namespace FPHP\Foundation\Coroutine;


class SysCall
{
    protected $callback = null;

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(Task $task)
    {
        return call_user_func($this->callback, $task);
    }
}