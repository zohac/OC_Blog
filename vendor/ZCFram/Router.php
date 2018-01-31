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
     * The name of the middelware
     * @var string
     */
    protected $middelware;

    /**
     * set of variables
     * @var array
     */
    protected $vars = [];

    /**
     * Loads the route file
     * @var string $routeFromXML
     */
    public function __construct(string $routeFromXML)
    {
        //Loads the route file in xml format
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
     * Get the name of the middelware
     * @return string
     */
    public function getMiddelware(): string
    {
        return $this->middelware;
    }

    /**
     * Check if a path exists in the configuration file
     * @param  string $uri path to test
     * @return boolean|object an exception if the route does not exist
     */
    public function match(string $uri):bool
    {
        // For each route contained in the route file...
        foreach ($this->routes as $route) {
            // ...We test if the uri passed matches a path in the file.
            if (preg_match('`^'.$route->getAttribute('url').'$`', $uri)) {
                // We see if variables are present in the URL.
                if ($route->hasAttribute('vars')) {
                    // We get the variable from the url...
                    $parts = \explode('-', $uri);
                    // ...And we make sure that the variable is an integer
                    $id = (int)str_replace('.html', '', \end($parts));

                    // Saving the value
                    $vars[$route->getAttribute('vars')] = $id;
                    $this->vars = $vars;
                }
                // See if there's any middelware.
                if ($route->hasAttribute('middelware')) {
                    $this->middelware = $route->getAttribute('middelware');
                }
                // Saving variables
                $this->module = $route->getAttribute('module');
                $this->action = $route->getAttribute('action');

                // And return true
                return true;
            }
        }
        // else we throw an exception
        throw new \RuntimeException('L\'URL demandée n\'existe pas.');
    }
}
