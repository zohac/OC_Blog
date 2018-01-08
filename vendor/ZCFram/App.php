<?php
namespace ZCFram;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Psr7\Response;

/**
 * Main application.
 * The entry point of the application.
 */
class App
{

    /**
     * Instance of the ServerRequest class
     * @var ServerRequest
     */
    protected $request;

    /**
     * Instance of the Response class
     * @var HTTPResponse
     */
    protected $reponse;

    /**
     * Initializes the http request
     * Initializes the Response
     */
    public function __construct()
    {
        $this->request = new HTTPRequest;
        $this->reponse = new HTTPResponse();

        $uri = $this->request->requestURI();

        // format the end of the url without '/'
        // and redirect to the correct url if necessary
        if (!empty($uri) && substr($uri, 0, -1) === '/' && strlen($uri) > 1) {
            $this->reponse->setStatus(301);
            $this->reponse->redirection(substr($uri, 0, -1));
        }
    }

    /**
     * Launch the application.
     * Check the uri and get the corresponding view.
     */
    public function run()
    {
        $uri = $this->request->requestURI();

        // else, launch the application
        try {
            $controller = $this->getController();
            $controller->execute();
            $view = $controller->getView();
            $this->reponse->send($view);
        } catch (\Exception $e) {
            var_dump($e);
            $error = (int) $e->getMessage();

            //$url = $this->request->serverName().'/'.$error.'.html';
            $url = '/'.$error.'.html';
            $this->reponse->setStatus($error);
            //$this->reponse->redirection($url);
        }
    }

    /**
     * Check the url and return the corresponding controller
     * @return [Instance of a controller class]
     */
    public function getController()
    {
        /**
         * We instantiate the router, and we check if the url
         * corresponds to a route in the configuration file.
         */
        $router = new Router(realpath(__DIR__.'/../../app/config/routes.xml'));

        $router->match($this->request->requestURI());

        // We add the variables of the URL to the $ _GET array.
        $_GET = array_merge($_GET, $router->getVars());

        // We instantiate the controller.
        $controllerClass = 'app\\'.$router->getModule().'Controller';
        return new $controllerClass($router->getAction());
    }
}
