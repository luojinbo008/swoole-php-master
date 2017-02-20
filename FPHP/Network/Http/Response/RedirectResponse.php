<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:37
 */
namespace FPHP\Network\Http\Response;

use FPHP\Contract\Network\Response as ResponseContract;

class RedirectResponse extends BaseRedirectResponse implements ResponseContract
{
    use ResponseTrait;
}