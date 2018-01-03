<?php
namespace app;

use \ZCFram\Controller;
use \ZCFram\viewController;

/**
 * [postsController description]
 */
class postsController extends Controller
{

    public function __construct($action)
    {
        parent::__construct($action);

        $this->view = new viewController();
    }

    /**
     * [executeIndex description]
     */
    public function executeIndex()
    {
        $this->view->setContent('index.twig');
    }

    /**
     * [getView description]
     * @return [type] [description]
     */
    public function getView()
    {
        return $this->view->getView();
    }
}
