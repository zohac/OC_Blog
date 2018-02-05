<?php
namespace app;

use ZCFram\Controller;

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
    protected $container;

    /**
     * Basic blog configuration.
     * @var array $appConfig
     */
    protected $appConfig;

    /**
     * Building the class.
     * @param DIC $container    An instance of the DIC
     */
    public function __construct(\ZCFram\DIC $container)
    {
        $this->container = $container;
        // Recovery of the configuration.
        $data = $this->container->get('Configurator');
        $this->appConfig = $data->getConfig('blog');
    }

    /**
     * We get the next controller and execute it.
     */
    public function execute()
    {
        //We get the router back.
        $router = $this->container->get('Router');

        //  We get the next controller
        $controllerClass = 'app\\'.$router->getModule().'Controller';
        $controller = new $controllerClass($this->container, $this->appConfig);

        // We execute it
        $controller->execute();
    }
}
