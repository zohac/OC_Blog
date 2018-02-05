<?php
/**
 * This file loads the autoloader and launches the application.
 */

// We load the autolaoder of Composer
require '../vendor/autoload.php';
require '../app/config/container.php';

$app = new \ZCFram\App($container);
$app->run();
