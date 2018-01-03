<?php
namespace app;

use \ZCFram\Controller;
use \ZCFram\viewController;

/**
 * Controller who manages the index and blog posts
 */
class PostsController extends Controller
{

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
        $this->view->setviewName('index.twig');
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
