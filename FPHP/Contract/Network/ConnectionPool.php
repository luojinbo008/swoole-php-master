<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 14:48
 */

namespace FPHP\Contract\Network;


interface ConnectionPool
{
    /**
     * ConnectionPool constructor.
     * @param ConnectionFactory $connectionFactory
     * @param array $config
     * @param $type
     */
    public function __construct(ConnectionFactory $connectionFactory, array $config, $type);

    /**
     * @param array $config
     * @return mixed
     */
    public function reload(array $config);

    /**
     * @return mixed
     * @TODO 服务器宕机处理???
     */
    public function get();

    /**
     * @param Connection $conn
     * @return mixed
     */
    public function remove(Connection $conn);

    /**
     * @param Connection $conn
     * @return mixed
     */
    public function recycle(Connection $conn);

}