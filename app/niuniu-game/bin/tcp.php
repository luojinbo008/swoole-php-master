#!/usr/bin/env php
<?php

$cmd = 'start';
switch ($cmd) {
    case 'start':
        /**
         * @var \FPHP\Foundation\App $app
         */
        $app = require_once __DIR__ . '/../init/app.php';
        $server = $app->createTcpServer();
        $server->start();
        break;
    case 'stop':
        break;
}