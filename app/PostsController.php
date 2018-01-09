<?php
namespace app;

use ZCFram\Controller;

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
        $this->setView();
    }

    /**
     * [executeListPosts description]
     * @return [type] [description]
     */
    public function executeListPosts()
    {
        $manager = $this->getManager();
        $this->setView();
    }
}
