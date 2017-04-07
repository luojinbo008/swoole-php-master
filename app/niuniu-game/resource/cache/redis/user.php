<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/4/7
 * Time: 17:06
 */
return [
    'common'    => [
        'connection'    => 'redis.user',
    ],
    'info' => [
        'key' => 'user_info_%s_%s',
        'exp' => 86400
    ],
];