<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:48
 */

namespace FPHP\Contract\Network;


interface Connection
{
    public function getSocket();

    public function release();

    public function close();

    public function getEngine();

    public function heartbeat();
}