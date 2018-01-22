<?php
namespace app;

use ZCFram\Controller;
use ZCFram\Container;

/**
 * Controller who manages the index and blog posts
 */
class PostController extends Controller
{

    /**
     * Execute the index page
     * Validation of the contact form.
     */
    public function executeIndex()
    {
        // If variables exist in the post method
        if (!empty($_POST)) {
            //Retrieving the class that validates the data sent
            $Validator = Container::getValidator();
            $Validator->required('name', 'text');
            $Validator->required('email', 'email');
            $Validator->required('comments', 'text');

            /*
             * If the validator does not return an error,
             * else adding error flash message
             */
            if (!$Validator->hasError()) {
                // Recovery of classes managing swiftMailer
                $mailer = Container::getMailer();
                $message = Container::getSwiftMessage();

                // Recovery of validated data
                $params = $Validator->getParams();

                // Create the message
                $message->setBody('
                    De : '.$params['name'].'
                    Email : '.$params['email'].'
                    Content : '.$params['comments']);

                // Send the message
                $result = $mailer->send($message);

                // Adding a flash message if successful or unsuccessful
                if ($result > 0) {
                    $this->flash->addFlash('success', 'E-mail envoyé avec succès. Merci '.$params['name'].'!');
                } else {
                    $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'envoi de mail.');
                }
            } else {
                foreach ($Validator->getError() as $key => $value) {
                    $this->flash->addFlash('danger', $value);
                }
            }
        }
        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());

        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Execute the blog page
     */
    public function executeListPost()
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();
        // Get the list of all post in DB
        $listPosts = $manager->getList();

        // Division in 2 of the list of posts
        // it's just necessary for the bootstrap theme used
        $listLeft = [];
        $listRight = [];

        foreach ($listPosts as $key => $list) {
            if (($key % 2) == 0) {
                $listRight[] = $list;
            } else {
                $listLeft[] = $list;
            }
        }

        // Adding parameters to return by the view
        $this->setParams([
            'left' => $listLeft,
            'right' => $listRight
        ]);

        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Execute the blog post page
     */
    public function executePost()
    {
        // we make sure that the variable $_GET['id'] is an integer
        $id = (int)$_GET['id'];

        // Recovery of the manager returned by the router
        $manager = $this->getManager();
        // Get one post in DB
        $Post = $manager->getPost($id);

        // We check the post for comments?
        if ($manager->postHasComment($id)) {
            // We change the manager
            $this->setManager('Comment');
            $manager = $this->getManager();

            // We retrieve comments
            $comment = $manager->getComment($id);
        } else {
            $this->setParams(['numberOfComments' => '0 Commentaire']);
        }

        // If the variable $_POST['comment'] exist
        if (isset($_POST['comment'])) {
            // Sent comment control
            $comment = new CommentController($this->router);
            $comment->CommentControl();
        }

        if ($this->user->isAuthenticated()) {
            $this->setParams([
                'post_id' => $id,
                'isAuthenticated' => true,
                'pseudo' => \ucfirst($this->user->getUserInfo('pseudo'))
            ]);
        }
        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());
        $this->setParams($Post);

        // View recovery and display
        $this->getView();
        $this->send();
    }
}
