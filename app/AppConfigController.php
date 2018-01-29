<?php
namespace app;

use ZCFram\Router;
use ZCFram\Controller;
use ZCFram\Container;

/**
 * Middelware
 * Retrieves the general configuration of the application.
 */
class AppConfigController extends Controller
{

    /**
     * An instance of the Router
     * @var object Router
     */
    protected $router;

    /**
     * Basic blog configuration.
     * @var array $appConfig
     */
    protected $appConfig;

    /**
     * Building the class.
     * @param Router $router    An instance of the Router
     */
    public function __construct(Router $router)
    {
        // Router registration.
        $this->router = $router;

        // Recovery of the configuration.
        $this->appConfig = Container::getConfigurator('blog');
    }

    /**
     * We get the next controller and execute it.
     */
    public function execute()
    {
        //  We get the next controller
        $controllerClass = 'app\\'.$this->router->getModule().'Controller';
        $controller = new $controllerClass($this->router, $this->appConfig);

        // We execute it
        $controller->execute();
    }
}
