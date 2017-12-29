<?php
namespace ZCFram;

/**
 * [Router description]
 */
class Router
{

    const NO_ROUTE = 1;

    /**
     * set of xml file paths
     * @var [type]
     */
    protected $routes;

    /**
     * The name of the application
     * @var string
     */
    protected $app;

    /**
     * The name of the module
     * @var string
     */
    protected $module;

    /**
     * The name of the action
     * @var string
     */
    protected $action;

    /**
     * set of variables
     * @var string[]
     */
    protected $vars = [];

    public function __construct($routeFromXML)
    {
        $xml = new \DOMDocument;
        $xml->load($routeFromXML);
        $this->routes = $xml->getElementsByTagName('route');
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Check if a path exists in the configuration file
     * @param  string $uri path to test
     * @return boolean
     */
    public function match($uri)
    {
        foreach ($this->routes as $route) {
            if (preg_match('`^'.$route->getAttribute('url').'$`', $uri, $matches)) {
                $vars = [];

                // On regarde si des variables sont présentes dans l'URL.
                if ($route->hasAttribute('vars')) {
                    $vars = explode(',', $route->getAttribute('vars'));
                }

                $this->app = $route->getAttribute('app');
                $this->module = $route->getAttribute('module');
                $this->action = $route->getAttribute('action');
                $this->vars = $vars;
                return true;
            }
        }
        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }
}
