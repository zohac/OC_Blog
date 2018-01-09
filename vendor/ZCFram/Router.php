<?php
namespace ZCFram;

/**
 * The Router
 */
class Router
{

    /**
     * set of xml file paths
     * @var \DOMDocument
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
     * Check if a path exists in the configuration file
     * @param  string $uri path to test
     * @return boolean [or raise an exception if the route does not exist]
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
        throw new \RuntimeException('L\'URL demandée n\'existe pas.');
    }
}
