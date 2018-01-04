<?php
namespace app;

use \ZCFram\Controller;
use \ZCFram\ViewController;

/**
 * Controller who manages the index and blog posts
 */
class PostsController extends Controller
{

    /**
     * An instance of the view controller
     * @var ViewController
     */
    protected $view;

    public function __construct($action)
    {
        parent::__construct($action);

        $this->view = new ViewController();
    }

    /**
     * Execute the index page
     */
    public function executeIndex()
    {
        $this->view->setviewName(\strtolower($this->action).'.twig');
    }

    /**
     * Override the methode of Controller
     * @return string
     */
    public function getView(): string
    {
        return $this->view->getView();
    }
}
