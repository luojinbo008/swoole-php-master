<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 22:34
 */
namespace FPHP\Foundation;
use RuntimeException;
use FPHP\Util\Types\Arr;
use FPHP\Foundation\Init\InitDi;
use FPHP\Foundation\Init\LoadConfig;
use FPHP\Foundation\Init\InitDebug;
use FPHP\Foundation\Init\InitEnv;
use FPHP\Foundation\Init\InitPathes;
use FPHP\Foundation\Init\InitRunMode;
use FPHP\Foundation\Container\Container;
use FPHP\Network\Server\Factory as ServerFactory;

class App
{

    /**
     * The framework version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    /**
     * The name for the App.
     *
     * @var string
     */
    protected $appName;

    /**
     * The base path for the App installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The application namespace.
     *
     * @var null
     */
    protected $namespace = null;

    /**
     * @var \FPHP\Foundation\Container\Container
     */
    protected $container;

    /**
     * App constructor.
     * @param $appName
     * @param $basePath
     */
    public function __construct($appName, $basePath)
    {
        $this->appName = $appName;
        $this->setBasePath($basePath);

        // 初始化项目
        $this->bootstrap();

        static::setInstance($this);
    }

    /**
     * 初始化项目
     */
    protected function bootstrap()
    {
        $this->setContainer();

        $bootstrapItems = [
            InitDebug::class,
            InitEnv::class,
            InitRunMode::class,
            InitPathes::class,
            LoadConfig::class,
            InitDi::class,
        ];
        foreach ($bootstrapItems as $bootstrap) {
            $this->make($bootstrap)->bootstrap($this);
        }
    }

    /**
     * 注册容器
     *
     * @param $abstract
     * @param array $parameters
     * @param bool $shared
     * @return mixed
     */
    public function make($abstract, array $parameters = [], $shared = false)
    {
        return $this->container->make($abstract, $parameters, $shared);
    }

    /**
     * 确定 控制台是否运行
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli';
    }

    /**
     * 获得 项目名称.
     *
     * @return string
     */
    public function getName()
    {
        return $this->appName;
    }

    /**
     * 设置 项目 目录
     *
     * @param $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
        return $this;
    }

    /**
     * 获得 项目 目录
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * 活动 项目 目录
     *
     * @return string
     */
    public function getAppPath()
    {
        return $this->basePath . '/' . 'src';
    }

    /**
     * 设置 容器
     *
     * @return $this
     */
    public function setContainer()
    {
        $this->container = new Container();
        return $this;
    }

    /**
     * 获得 容器
     *
     * @return \FPHP\Foundation\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * 获得 app 全局 实例
     *
     * @return static
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * 设置 app 全局 实例.
     *
     * @param  \FPHP\Foundation\App  $app
     * @return void
     */
    public static function setInstance($app)
    {
        static::$instance = $app;
    }

    public function getNamespace()
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }
        $composer = json_decode(
            file_get_contents($this->getBasePath() . '/' . 'composer.json'),
            true
        );
        foreach ((array) Arr::get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath($this->getAppPath()) == realpath($this->getBasePath().'/'.$pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }
        throw new RuntimeException('Unable to detect application namespace.');
    }

    /**
     * 获得 http server.
     *
     * @return \FPHP\Network\Http\Server
     */
    public function createHttpServer()
    {
        return $this->getContainer()
            ->make(ServerFactory::class)
            ->createHttpServer();
    }

    public function createWebSocketServer()
    {
        return $this->getContainer()
            ->make(ServerFactory::class)
            ->createWebSocketServer();
    }
}