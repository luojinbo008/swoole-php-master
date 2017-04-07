<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/22
 * Time: 10:06
 */
return [
    'user' => [
        'engine'    => 'redis',
        'host'      => '127.0.0.1',
        'port'      => 6379,
        'pool'      => [
            'init-connection'       => 8,
            'heartbeat-time'        => 5000
        ],
    ],
];