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
     * Execute the index page
     */
    public function executeIndex()
    {
        $this->setView(\strtolower($this->action).'.twig');
    }
}
