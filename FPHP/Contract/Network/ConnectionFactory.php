<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:36
 */

namespace FPHP\Contract\Network;


interface ConnectionFactory
{
    /**
     * ConnectionFactory constructor.
     * @param array $config
     * @TODO 负载均衡
     */
    public function __construct(array $config);

    public function create();

    public function close();

}