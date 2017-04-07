<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/31
 * Time: 9:37
 */

namespace FPHP\Network\Tcp\Response;

class BaseResponse
{

    const TCP_OK        = 200;
    const TCP_ERROR     = 500;
    const TCP_NOT_FOUND = 404;

    /**
     * @var ResponseHeaderBag
     */
    public $headers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $statusText;

    /**
     * @var string
     */
    protected $charset;

    /**
     * @var array
     */
    public static $statusTexts = [
        200 => 'OK',
        404 => '404 Not Found'
    ];

    /**
     * BaseResponse constructor.
     * @param string $content
     * @param int $status
     */
    public function __construct($content = '', $status = self::TCP_OK, $headers = [])
    {
        $this->headers = new ResponseHeaderBag($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }

    /**
     * @param string $content
     * @param int $status
     * @return static
     */
    public static function create($content = '', $status = self::TCP_OK, $headers = [])
    {
        return new static($content, $status, $headers);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }

    public function setContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content)
            && !is_callable(array($content, '__toString'))) {
            throw new \UnexpectedValueException(
                sprintf('The Response content must be a string or object implementing __toString(), "%s" given.',
                    gettype($content)
                )
            );
        }
        $this->content = (string) $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $version
     * @return $this
     */
    public function setProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param $code
     * @param null $text
     * @return $this
     */
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = $code = (int) $code;

        if (null === $text) {
            $this->statusText = isset(self::$statusTexts[$code]) ? self::$statusTexts[$code] : 'unknown status';
            return $this;
        }

        if (false === $text) {
            $this->statusText = '';
            return $this;
        }

        $this->statusText = $text;
        return $this;
    }
}