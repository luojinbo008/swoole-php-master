<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/7
 * Time: 17:51
 */

namespace FPHP\Contract\Store\Database;


interface ResultFormatterInterface
{
    /**
     * ResultFormatterInterface constructor.
     * @param DbResultInterface $result
     * @param int $resultType
     */
    public function __construct(DbResultInterface $result, $resultType = ResultTypeInterface::RAW);

    /**
     * @return mixed(base on ResultType)
     */
    public function format();
}