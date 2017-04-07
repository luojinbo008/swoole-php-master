<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/31
 * Time: 18:24
 */

namespace FPHP\Network\Tcp\Response;


class ResponseHeaderBag
{
    public $headers = [];
    /**
     * ResponseHeaderBag constructor.
     * @param array $headers
     */
    public function __construct(array $headers = [])
    {

    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists(strtolower($key), $this->headers);
    }

    /**
     * @param $key
     * @param $values
     * @param bool $replace
     */
    public function set($key, $values, $replace = true)
    {
        $key = strtolower($key);
        $values = array_values((array) $values);

        if (true === $replace || !isset($this->headers[$key])) {
            $this->headers[$key] = $values;
        } else {
            $this->headers[$key] = array_merge($this->headers[$key], $values);
        }
    }

}