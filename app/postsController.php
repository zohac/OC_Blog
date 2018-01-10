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
        $listPosts = $manager->getList();

        $listLeft = [];
        $listRight = [];

        foreach ($listPosts as $key => $list) {
            if (($key % 2) == 0) {
                $listRight[] = $list;
            } else {
                $listLeft[] = $list;
            }
        }

        $this->setParams([
            'left' => $listLeft,
            'right' => $listRight
        ]);
        $this->setView();
    }
}
