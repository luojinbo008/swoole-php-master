<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/24
 * Time: 11:02
 */
return [
    'common'    => [
        'connection'    => 'redis.default',
    ],
    'cc' => [
        'key' => 'test_abc_%s_%s',
        'exp' => 300
    ],
];