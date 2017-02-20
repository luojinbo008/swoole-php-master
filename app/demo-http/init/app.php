<?php

$appName = 'http-demo';
$basePath = realpath(__DIR__ . '/../');

require $basePath . '/vendor/autoload.php';

use FPHP\Foundation\App;

$app = new App($appName, $basePath);
return $app;
