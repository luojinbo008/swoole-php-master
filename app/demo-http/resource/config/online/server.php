<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/7
 * Time: 15:56
 */

return [
    'host'          => '192.168.56.101',
    'port'          => '8991',
    'config' => [
        'worker_num'    => 1,
    ],
    'monitor' => [
        'max_request'   => 100000,
        'max_live_time' => 1800000,
        'check_interval'=> 10000,
        'memory_limit'  => 1.5 * 1024 * 1024 * 1024,
        'cpu_limit'     => 70,
        'debug'         => false
    ]
];