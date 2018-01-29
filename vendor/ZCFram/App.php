<?php
namespace ZCFram;

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
        $ticket = new SessionTicket;

        // We're recovering the client request.
        $this->request = new HTTPRequest();
        $uri = $this->request->requestURI();

        // format the end of the url without '/'
        // and redirect to the correct url if necessary
        if ($ticket->isTicketValid() && !empty($uri) && substr($uri, -1, 1) === '/' && strlen($uri) > 1) {
            $this->reponse = Container::getHTTPResponse();
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
        // try to launch the application
        // else get the Exception
        try {
            // We retrieve the controller (request for the Router)
            $controller = $this->getController();
            // We execute it
            $controller->execute();
        } catch (\Exception $e) {
            $controller = new ErrorController($e);
            $controller->execute();
        }
    }

    /**
     * Check the url and return the corresponding controller
     * @return [Instance of a controller class]
     */
    public function getController()
    {

        // We instantiate the router, and we check if the url
        // corresponds to a route in the configuration file.
        $router = new Router(realpath(__DIR__.'/../../app/config/routes.xml'));
        $router->match($this->request->requestURI());

        // We add the variables of the URL to the $_GET array.
        $_GET = array_merge($_GET, $router->getVars());

        // If there's a middelware, we go through it,
        // otherwise we get the module.
        if ($router->getMiddelware()) {
            // We instantiate the controller.
            $controllerClass = 'app\\'.$router->getMiddelware().'Controller';
        } else {
            // We instantiate the controller.
            $controllerClass = 'app\\'.$router->getModule().'Controller';
        }
        // We return an instance of the desired controller
        return new $controllerClass($router);
    }
}
