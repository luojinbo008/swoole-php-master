<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/18
 * Time: 12:35
 */

namespace FPHP\Network\Http\Response;

use FPHP\Contract\Network\Response as ResponseContract;

class InternalErrorResponse extends BaseResponse implements ResponseContract
{
    use ResponseTrait;
}