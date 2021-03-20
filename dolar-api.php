#!/usr/bin/php
<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use DolarApi\App;

$app = new App();
$app->displayConversion($argv);
