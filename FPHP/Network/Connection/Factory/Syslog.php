<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:08
 */

namespace FPHP\Network\Connection\Factory;

use FPHP\Contract\Network\ConnectionFactory;

class Syslog implements ConnectionFactory
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function create()
    {

    }

    public function close()
    {

    }

    public function heart()
    {
    }
}