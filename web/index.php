<?php
/**
 * The entry point of the application.
 * This file loads the autoloader and launches the application.
 */

require '../vendor/autoload.php';

$app = new \ZCFram\App();
$app->run();

// Sends the answer of the application to the browser
\Http\Response\send($app->getResponse());
