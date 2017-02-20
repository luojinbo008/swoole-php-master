<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/14
 * Time: 17:20
 */

namespace FPHP\Foundation\Init;

use FPHP\Contract\Foundation\Initable;
use FPHP\Foundation\App;
use FPHP\Foundation\Container\Di;

class InitDi implements Initable
{

    /**
     * @var \FPHP\Foundation\App
     */
    private $app;

    /**
     * @param App $app
     */
    public function bootstrap(App $app)
    {
        $this->app = $app;
        $this->initDiFacade();
    }

    private function initDiFacade()
    {
        Di::resolveFacadeInstance($this->app->getContainer());
    }

}