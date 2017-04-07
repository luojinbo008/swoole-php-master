<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/4/7
 * Time: 17:37
 */

namespace Com\NiuNiu\Src\Controller\Main;

use FPHP\Foundation\Domain\TcpController;
use Com\NiuNiu\Src\Module\Model\User\UserModel;

class UserController extends TcpController
{
    public function get()
    {
        $userModel = new UserModel();
        $data =  $userModel->getInfo(1, 1);
        yield $this->r(200, "2222", $data);
    }
}