<?php
namespace app;

use ZCFram\Controller;
use ZCFram\Container;

/**
 * Controller who manages the index and blog posts
 */
class PostsController extends Controller
{

    /**
     * Execute the index page
     * Validation of the contact form.
     */
    public function executeIndex()
    {
        if (!empty($_POST)) {
            $Validator = Container::getValidator();

            $Validator->required('name', 'text');
            $Validator->required('email', 'email');
            $Validator->required('comments', 'text');

            if (!$Validator->hasError()) {
                // TO DO : swiftMail
            }

            $this->setParams($Validator->getParams());
            $this->setView('alertControlForm');
        }
    }

    /**
     * Execute the blog page
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
    }

    /**
     * Execute the blog post page
     */
    public function executePost()
    {
        $manager = $this->getManager();
        $Post = $manager->getPost($_GET['id']);

        $this->setParams($Post);
    }
}
