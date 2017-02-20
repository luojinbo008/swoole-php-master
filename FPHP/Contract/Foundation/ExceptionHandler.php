<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 12:49
 */

namespace FPHP\Contract\Foundation;


interface ExceptionHandler
{
    /**
     * @param \Exception $e
     * @return mixed
     */
    public function handle(\Exception $e);
}