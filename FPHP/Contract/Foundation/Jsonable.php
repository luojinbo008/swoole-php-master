<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 14:34
 */

namespace FPHP\Contract\Foundation;


interface Jsonable
{
    /**
     * @param int $options
     * @return mixed
     */
    public function toJson($options = 0);
}