<?php

$config = [
    'HTTPRequest' => function () {
        return new \ZCFram\HTTPRequest();
    },
    'HTTPResponse' => function () {
        return new \ZCFram\HTTPResponse();
    },
    'Router' => function () {
        return new \ZCFram\Router(realpath(__DIR__.'/../../app/Config/routes.xml'));
    },
    'Validator' => function () {
        return new \ZCFram\Validator();
    },
    'Flash' => function () {
        return new \ZCFram\Flash();
    },
    'Configurator' => function () {
        return new \ZCFram\Configurator(realpath(__DIR__.'/../../app/Config/config.xml'));
    },
    'Token' => function () {
        return new \ZCFram\Token();
    },
    'Encryption' => function () {
        return new \ZCFram\Encryption();
    },
    'User' => function () {
        return new \app\Entity\User();
    }
];

$container = new \ZCFram\DIC($config);

$container->set('Ticket', function () use ($container) {
    return new \ZCFram\SessionTicket($container->get('HTTPResponse'));
});
$container->set('Email', function () use ($container) {
    return new \ZCFram\Email($container->get('Flash'), $container->get('Validator'), $container->get('Configurator'));
});
$container->set('CommentController', function () use ($container) {
    return new \app\Controller\CommentController($container->get('Router'), $container);
});
