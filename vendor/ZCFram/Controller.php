<?php
namespace ZCFram;

/**
 * Manages all controllers in the application
 */
abstract class Controller
{

    /**
     * The name of the method to invoke.
     * @var string
     */
    protected $action;

    /**
     * The name of the manager to invoke.
     * @var string
     */
    protected $manager;

    /**
     * An instance of the view controller
     * @var
     */
    protected $view;

    /**
     * The parameters to pass to twig
     * @var array
     */
    protected $params = [];

    /**
     * Represents a user.
     * @var object user
     */
    protected $user;

    /**
     * Represents flash messages
     * @var object flash
     */
    protected $flash;

    /**
     * An instance of the Router
     * @var objet
     */
    protected $router;

    /**
     * An instance of the DIC
     * @var object DIC
     */
    protected $container;

    /**
     * Set the variable name
     * @param string
     */
    public function __construct(\ZCFram\DIC $container, array $params = [])
    {
        $this->container = $container;

        $this->router = $this->container->get('Router');
        $this->flash =  $this->container->get('Flash');
        $this->user =  $this->container->get('User');

        $this->setAction($this->router->getAction());
        $this->setManager($this->router->getModule());
        $this->setView($this->action);

        if ($params) {
            $this->setParams($params);
        }



        $this->setParams(['user' => $this->user]);
    }

    /**
     * Execute the method
     */
    public function execute()
    {
        // Formatting the method name
        $method = 'execute'.ucfirst($this->action);

        // We're checking to see if the method exists.
        // We throw an exception in the event of an error
        if (!is_callable([$this, $method])) {
            throw new \BadFunctionCallException('La méthode utilisée n\'existe pas.');
        }
        // We execute the method
        $this->$method();
    }

    /**
     * Set the name of the manager
     * @param string $manager
     */
    protected function setManager(string $manager)
    {
        // We check if the weak variable is a non-null character string
        // We throw an exception in the event of an error
        if (!is_string($manager) || empty($manager)) {
            throw new \InvalidArgumentException('Le manager demandé n\'existe pas.');
        }
        // we store the variable
        $this->manager = $manager;
    }

    /**
     * Set the name of the action to do
     * @param string
     */
    protected function setAction(string $action)
    {
        // We check if the weak variable is a non-null character string
        // We throw an exception in the event of an error
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException('L\'action demandée n\'existe pas.');
        }
        // we store the variable
        $this->action = $action;
    }

    /**
     * Set the name of the action to do
     * @param string
     */
    protected function setApplication(string $app)
    {
        // We check if the weak variable is a non-null character string
        // We throw an exception in the event of an error
        if (!is_string($app) || empty($app)) {
            throw new \InvalidArgumentException('L\'application demandée n\'existe pas.');
        }
        // we store the variable
        $this->app = $app;
    }

    /**
     * Set the parameters for the views
     * @param array $params
     */
    protected function setParams(array $params)
    {
        // We check if the variable is an array
        // We throw an exception in the event of an error
        if (!is_array($params)) {
            throw new \InvalidArgumentException('Les paramètres ne sont pas au bon format.');
        }
        // we store the variable
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Set the name of the view for the renderer
     * @param string $view
     */
    protected function setView(string $view = null)
    {
        // Formatting the view name
        $this->view = \strtolower($view).'.twig';
    }

    /**
     * Get an instance of a manager
     * @return object Return of an instance of a manager
     */
    protected function getManager()
    {
        // Definition of the manager path to be retrieved
        $managerClass = '\app\\model\\'.$this->manager.'Manager';

        $data = $this->container->get('Configurator');
        $data = $data->getConfig('database');

        // Return of an instance of a manager
        return new $managerClass($data);
    }

    /**
     * Generate the requested view with twig.
     * @return string Return the view
     */
    public function getView()
    {
        // Instancies twig's environment
        $loader = new \Twig_Loader_Filesystem(realpath(__DIR__.'/../../app/views'));
        $twig = new \Twig_Environment($loader, []);
//        \var_dump($_SESSION);

        // Recovers the view generated by twig
        $this->view =  $twig->render($this->view, $this->params);
    }

    /**
     * Retrieve the response and send it to the client browser
     */
    public function send()
    {
        $response = $this->container->get('HTTPResponse');
        $response->send($this->view);
    }
}
