<?php
/**
 * This file loads the autoloader and launches the application.
 */

require '../vendor/autoload.php';

$app = new \ZCFram\App();
$app->run();
