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
                $mailer = Container::getMailer();
                $message = Container::getSwiftMessage();

                $params = $Validator->getParams();
                // Give the message a subject
                $message->setBody('
                    De : '.$params['name'].'
                    Email : '.$params['email'].'
                    Content : '.$params['comments']);

                // Send the message
                $mailer->send($message);
            }
            $this->setParams($Validator->getParams());
        }
        $this->getView();
        $this->send();
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

        $this->getView();
        $this->send();
    }

    /**
     * Execute the blog post page
     */
    public function executePost()
    {
        $manager = $this->getManager();
        $Post = $manager->getPost($_GET['id']);

        $this->setParams($Post);

        $this->getView();
        $this->send();
    }
}
