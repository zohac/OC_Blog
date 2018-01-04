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
            throw new \RuntimeException('L\'action "'.$this->action.'" n\'est pas définie sur ce module');
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
            throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
        }

        $this->action = $action;
    }
}
