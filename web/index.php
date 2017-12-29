<?php
//Autoloader
require '../vendor/autoload.php';

$app = new \ZCFram\App();
$app->run();
\Http\Response\send($app->getResponse());
