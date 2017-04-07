<?php

$appName = 'niuniu-game';
$basePath = realpath(__DIR__ . '/../');

require $basePath . '/vendor/autoload.php';

use FPHP\Foundation\App;

putenv('FPHP_RUN_MODE=dev');

$app = new App($appName, $basePath);
return $app;
