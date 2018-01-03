<?php
namespace ZCFram;

/**
 * [abstract description]
 * @var [type]
 */
abstract class Controller
{

    /**
     * [protected description]
     * @var [type]
     */
    protected $action;

    /**
     * [protected description]
     * @var [type]
     */
    protected $view;

    /**
     * [__construct description]
     * @param [type] $action [description]
     */
    public function __construct($action)
    {
        $this->setAction($action);
    }

    /**
     * [execute description]
     * @return [type] [description]
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
     * [setAction description]
     * @param [type] $action [description]
     */
    protected function setAction($action)
    {
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
        }

        $this->action = $action;
    }

    /**
     * [getView description]
     * @return [type] [description]
     */
    public function getView()
    {
        return $this->view;
    }
}
