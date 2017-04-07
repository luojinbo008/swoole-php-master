<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/31
 * Time: 9:35
 */
namespace FPHP\Network\Tcp\Response;

use JsonSerializable;
use InvalidArgumentException;
use FPHP\Contract\Foundation\Jsonable;
use FPHP\Contract\Foundation\Arrayable;
use FPHP\Contract\Network\Response as ResponseContract;

class JsonResponse extends BaseJsonResponse implements ResponseContract
{
    use ResponseTrait;

    /**
     * JsonResponse constructor.
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param int $options
     */
    public function __construct($data = null, $status = self::TCP_OK, $headers = [], $options = 0)
    {
        $this->encodingOptions = $options;
        parent::__construct($data, $status, $headers);
    }

    /**
     * @param bool $assoc
     * @param int $depth
     * @return mixed
     */
    public function getData($assoc = false, $depth = 512)
    {
        return json_decode($this->data, $assoc, $depth);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data = [])
    {
        if ($data instanceof Arrayable) {
            $this->data = json_encode($data->toArray(), $this->encodingOptions);
        } elseif ($data instanceof Jsonable) {
            $this->data = $data->toJson($this->encodingOptions);
        } elseif ($data instanceof JsonSerializable) {
            $this->data = json_encode($data->jsonSerialize(), $this->encodingOptions);
        } else {
            $this->data = json_encode($data, $this->encodingOptions);
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        return $this->update();
    }

}