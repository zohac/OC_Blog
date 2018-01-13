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
     * Set the variable name
     * @param string
     */
    public function __construct($action, $manager)
    {
        $this->setAction($action);
        $this->setManager($manager);
        $this->setView($this->action);
    }

    /**
     * Execute the method
     */
    public function execute()
    {
        $method = 'execute'.ucfirst($this->action);

        if (!is_callable([$this, $method])) {
            throw new \BadFunctionCallException('La méthode utilisée n\'existe pas.');
        }

        $this->$method();
    }

    /**
     * Set the name of the manager
     * @param string
     */
    protected function setManager($manager)
    {
        if (!is_string($manager) || empty($manager)) {
            throw new \InvalidArgumentException('Le manager demandé n\'existe pas.');
        }

        $this->manager = $manager;
    }

    /**
     * Set the name of the action to do
     * @param string
     */
    protected function setAction($action)
    {
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException('L\'action demandée n\'existe pas.');
        }

        $this->action = $action;
    }

    /**
     * Set the parameters for the views
     * @param array $params
     */
    protected function setParams(array $params)
    {
        if (!is_array($params)) {
            throw new \InvalidArgumentException('Les paramètres ne sont pas au bon format.');
        }

        $this->params = array_merge($this->params, $params);
    }

    /**
     * Set the name of the view for the renderer
     * @param string $view
     */
    protected function setView(string $view = null)
    {
        $this->view = \strtolower($view).'.twig';
    }

    /**
     * Generate the requested view with twig.
     * @return object Return of an instance of a manager
     */
    protected function getManager()
    {
        $managerClass = '\app\\model\\'.$this->manager.'Manager';
        return new $managerClass(Container::getConnexionDB());
    }

    /**
     * Generate the requested view with twig.
     * @return string Return the view
     */
    public function getView()
    {
        $loader = new \Twig_Loader_Filesystem(realpath(__DIR__.'/../../app/views'));
        $this->twig = new \Twig_Environment($loader, []);

        return $this->twig->render($this->view, $this->params);
    }
}
