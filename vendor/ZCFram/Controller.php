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
    public function __construct($action)
    {
        $this->setAction($action);
    }

    /**
     * Execute the method
     */
    public function execute()
    {
        $method = 'execute'.ucfirst($this->action);

        if (!is_callable([$this, $method])) {
            throw new \RuntimeException(500);
        }

        $this->$method();
    }

    /**
     * Set the variable name
     * @param string
     */
    protected function setAction($action)
    {
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException(500);
        }

        $this->action = $action;
    }

    /**
     * [setParams description]
     * @param array $params [description]
     */
    protected function setParams(array $params)
    {
        if (!is_array($params)) {
            throw new \InvalidArgumentException(500);
        }

        $this->params = $params;
    }

    public function setView(string $view)
    {
        if (!is_string($view) || empty($view)) {
            throw new \InvalidArgumentException(500);
        }

        $this->view = $view;
    }

    /**
     * Generate the requested view with twig.
     * @return string Return the view
     */
    public function getView(): string
    {
        $loader = new \Twig_Loader_Filesystem(realpath(__DIR__.'/../../app/views'));
        $this->twig = new \Twig_Environment($loader, []);

        return $this->twig->render($this->view, $this->params);
    }
}
