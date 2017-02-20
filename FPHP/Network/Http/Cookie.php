<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/16
 * Time: 10:16
 */

namespace FPHP\Network\Http;

use FPHP\Foundation\Core\Config;
use FPHP\Foundation\Exception\System\InvalidArgumentException;
use FPHP\Network\Http\Request\Request;

use swoole_http_response as SwooleHttpResponse;

class Cookie
{
    private $configKey = 'cookie';
    private $config;
    private $request;
    private $response;

    public function __construct(Request $request, SwooleHttpResponse $swooleResponse)
    {
        $this->init($request, $swooleResponse);
    }

    private function init(Request $request, SwooleHttpResponse $swooleResponse)
    {
        $config = Config::get($this->configKey, null);
        if (!$config) {
            throw new InvalidArgumentException('cookie config is required');
        }
        $this->config = $config;
        $this->request = $request;
        $this->response = $swooleResponse;
    }

    public function get($key, $default = null)
    {
        $cookies = $this->request->cookies;
        if (!$key) {
            yield $default;
        }
        yield $cookies->get($key, $default);
    }

    public function set($key, $value = null, $expire = null, $path = null, $domain = null,
                        $secure = null, $httpOnly = null)
    {
        if (!$key) {
            return false;
        }
        if (null === $expire) {
            $expire = isset($this->config['expire']) ? $this->config['expire'] : 0;
        }
        $expire = time() + (int)$expire;

        $path = (null !== $path) ? $path : $this->config['path'];
        $domain = (null !== $domain) ? $domain : $this->request->getHost();
        $secure = (null !== $secure) ? $secure : $this->config['secure'];
        $httpOnly = (null !== $httpOnly) ? $httpOnly : $this->config['httponly'];

        $this->response->cookie($key, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}