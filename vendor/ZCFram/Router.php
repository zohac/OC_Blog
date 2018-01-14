<?php
namespace ZCFram;

/**
 * The Router
 */
class Router
{

    /**
     * set of xml file paths
     * @var object \DOMDocument
     */
    protected $routes;

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
     * The name of the application
     * @var string
     */
    protected $application;

    /**
     * set of variables
     * @var array
     */
    protected $vars = [];

    public function __construct($routeFromXML)
    {
        $xml = new \DOMDocument;
        $xml->load($routeFromXML);
        $this->routes = $xml->getElementsByTagName('route');
    }

    /**
     * Get the module name
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * Get the action name
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Get the variables in the url
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * Get the name of the application
     * @return string
     */
    public function getApp(): string
    {
        return $this->application;
    }

    /**
     * Check if a path exists in the configuration file
     * @param  string $uri path to test
     * @return boolean|object an exception if the route does not exist
     */
    public function match($uri)
    {
        foreach ($this->routes as $route) {
            if (preg_match('`^'.$route->getAttribute('url').'$`', $uri)) {
                $vars = [];

                $parts = \explode('-', $uri);
                $id = (int)str_replace('.html', '', \end($parts));

                // On regarde si des variables sont présentes dans l'URL.
                if ($route->hasAttribute('vars')) {
                    $vars[$route->getAttribute('vars')] = $id;
                }

                $this->application = $route->getAttribute('app');
                $this->module = $route->getAttribute('module');
                $this->action = $route->getAttribute('action');
                $this->vars = $vars;
                return true;
            }
        }
        throw new \RuntimeException('L\'URL demandée n\'existe pas.');
    }
}
