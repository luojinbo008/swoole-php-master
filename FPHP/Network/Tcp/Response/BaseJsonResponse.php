<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/31
 * Time: 14:04
 */

namespace FPHP\Network\Tcp\Response;


class BaseJsonResponse extends BaseResponse
{
    protected $data;
    protected $callback;

    protected $encodingOptions = 15;

    /**
     * BaseJsonResponse constructor.
     * @param null $data
     * @param int $status
     * @param array $headers
     */
    public function __construct($data = null, $status = self::TCP_OK, $headers = [])
    {
        parent::__construct('', $status, $headers);
        if (null === $data) {
            $data = new \ArrayObject();
        }
        $this->setData($data);
    }

    /**
     * @param null $data
     * @param int $status
     * @param array $headers
     * @return static
     */
    public static function create($data = null, $status = self::TCP_OK, $headers = [])
    {
        return new static($data, $status, $headers);
    }

    /**
     * Sets the data to be sent as JSON.
     *
     * @param mixed $data
     *
     * @return BaseJsonResponse
     * @throws \Exception
     */
    public function setData($data = array())
    {

        try {
            $data = json_encode($data, $this->encodingOptions);
        } catch (\Exception $e) {
            if ('Exception' === get_class($e) && 0 === strpos($e->getMessage(), 'Failed calling ')) {
                throw $e->getPrevious() ?: $e;
            }
            throw $e;
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }
        $this->data = $data;
        return $this->update();
    }

    /**
     * @return $this
     */
    protected function update()
    {
        $this->headers->set('type', 'application/json');
        return $this->setContent($this->data);
    }
}
