<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/7
 * Time: 18:05
 */
return [
    'default' => [
        'engine'    => 'mysqli',
        'host'      => '192.168.136.45',
        'user'      => 'webuser',
        'password'  => '123456',
        'database'  => 'test',
        'port'      => '3306',
        'pool'      => [
            'maximum-connection-count'  => 50,
            'minimum-connection-count'  => 1,
            'heartbeat-time'            => 5000,
            'init-connection'           => 1,
        ],
    ],

];