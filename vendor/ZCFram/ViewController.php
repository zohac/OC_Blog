<?php
namespace ZCFram;

/**
 * Controller who instantiates the twig renderer
 */
class ViewController
{

    /**
     * The parameters to pass to twig
     * @var array
     */
    protected $params = [];

    /**
     * The view name to invoke by twig
     * @var string
     */
    protected $viewName;

    /**
     * An instance of Twig_Environment
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * Instance of the Twig renderer
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(realpath(__DIR__.'/../../app/views'));
        $this->twig = new \Twig_Environment($loader, []);
    }

    /**
     * Set the name of the view to load
     * @param  string
     */
    public function setviewName(string $viewName)
    {
        $this->viewName = $viewName;
    }

    /**
     * Set the parameters for the view
     * @param array
     */
    public function setParams(array $params = null)
    {
        $this->params = $params;
    }

    /**
     * Return the view
     * @return string
     */
    public function getView(): string
    {
        return $this->twig->render($this->viewName, $this->params);
    }
}
