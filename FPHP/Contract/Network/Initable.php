<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:27
 */

namespace FPHP\Contract\Network;


interface Initable
{
    public function bootstrap($server);
}