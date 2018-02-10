<?php
namespace ZCFram;

/**
 * Main application.
 * The entry point of the application.
 */
class App
{
    /**
     * An instance of the DIC
     * @var object DIC
     */
    protected $container;

    /**
     * An instance of HTTPRequest
     * @var object HTTPRequest
     */
    protected $request;

    /**
     * Initializes the http request
     * Initializes the Response
     */
    public function __construct(DIC $container)
    {
        // Register of the container through the parameters
        $this->container = $container;

        // We're recovering the client request.
        $this->request = $this->container->get('HTTPRequest');
        $uri = $this->request->requestURI();

        // Ticket creation to prevent session theft
        $ticket = $this->container->get('Ticket');

        // Check if the ticket is valid
        if (!$ticket->isTicketValid()) {
            // Deleting User Authentication
            $user = $this->container->get('User');
            $user->setAuthenticated(false);
        }

        // Format the end of the url without '/'
        // and redirect to the correct url if necessary
        if (!empty($uri) && substr($uri, -1, 1) === '/' && strlen($uri) > 1) {
            // Redirection
            $reponse = $this->container->get('HTTPResponse');
            $reponse->setStatus(301);
            $reponse->redirection(substr($uri, 0, -1));
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
            // An instance of the error controller is created
            $controller = new ErrorController($this->container, $e);
            // We execute it
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
        $router = $this->container->get('Router');
        $router->match($this->request->requestURI());

        // We add the variables of the URL to the $_GET array.
        $_GET = array_merge($_GET, $router->getVars());

        // we get the module.
        // We instantiate the controller.
        $controllerClass = 'app\\Controller\\'.$router->getModule().'Controller';

        // We return an instance of the desired controller
        return new $controllerClass($this->container);
    }
}
