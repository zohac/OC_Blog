<?php
namespace ZCFram;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Psr7\Response;

/**
 *
 */
class App
{

    protected $appName;
    protected $moduleName;
    protected $params;
    protected $request;
    protected $reponse;

    /**
     * Initializes the http request
     * Initializes the Response
     */
    public function __construct()
    {
        $this->request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
        $this->reponse = new Response();
    }

    /**
     * Launch the application.
     * Check the uri and get the corresponding view
     */
    public function run()
    {
        $uri = $this->request->getUri()->getPath();

        if (!empty($uri) && $uri[-1] === '/' && strlen($uri) > 1) {
            $this->reponse = (new Response())
                                ->withStatus(301)
                                ->withHeader('Location', substr($uri, 0, -1));
        } else {
            try {
                $controller = $this->getController();
                $controller->execute();
                $view = $controller->getView();
                $this->reponse->getBody()->write($view);
            } catch (\Exception $e) {
                    var_dump($e->getMessage());
            }
        }
    }

    /**
     * Check the url and return the corresponding controller
     * @return [Instance of a controller class]
     */
    public function getController()
    {
        $routeFromXML = realpath(__DIR__.'/../../app/config/routes.xml');

        /**
         * We instantiate the router, and we check if the url
         * corresponds to a route in the configuration file.
         */
        $router = new Router($routeFromXML);
        $router->match($this->request->getUri()->getPath());

        // We add the variables of the URL to the $ _GET array.
        $_GET = array_merge($_GET, $router->getVars());

        // We instantiate the controller.
        $controllerClass = 'app\\'.$router->getModule().'Controller';
        return new $controllerClass($router->getAction());
    }

    /**
     * Returns the response of the ResponseInterface class
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->reponse;
    }
}
