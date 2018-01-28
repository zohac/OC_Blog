<?php
namespace app;

use ZCFram\Router;
use ZCFram\Controller;
use ZCFram\Container;

class AppConfigController extends Controller
{

    protected $router;
    protected $appConfig;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $config = Container::getConfigurator('blog');
        $this->appConfig = $config->getConfig();
    }

    public function execute()
    {
        // We instantiate the controller.
        $controllerClass = 'app\\'.$this->router->getModule().'Controller';
        $controller = new $controllerClass($this->router, $this->appConfig);
        $controller->execute();
    }
}
