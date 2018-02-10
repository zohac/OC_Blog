<?php
namespace app\Controller;

use ZCFram\Controller;

/**
 * Controller who manages the index and blog posts
 */
class FrontOfficeController extends Controller
{

    public function __construct(\ZCFram\DIC $container)
    {
        parent::__construct($container);

        // Set the manager
        $this->setManager('Post');
    }
    /**
     * Execute the index page
     * Validation of the contact form.
     */
    public function executeIndex()
    {
        //Retrieving the class that validates the token
        $token = $this->container->get('Token');

        // If variables exist in the post method
        if (!empty($_POST)) {
            // We're checking the validity of the token.
            if ($token->isTokenValid($_POST['token'])) {
                $email = $this->container->get('Email');
                $this->flash = $email->validateAndSendEmail();
            } else {
                $this->flash->addFlash('danger', 'Le formulaire n\est pas conforme.');
            }
        }
        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());
        //Retrieving the class that validates the token
        $token = $token->getToken();
        // Adding token to the parameters to return by the view
        $this->setParams(['token' => $token]);

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
        $listPosts = $manager->getListOfPost();

        // Division in 2 of the list of posts
        // it's just necessary for the bootstrap theme used
        $listLeft = [];
        $listRight = [];

        // For each post, the linked image is retrieved for each post, otherwise a default one is displayed.
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
        $post = $manager->getPost($id);

        // We change the manager
        $this->setManager('Comment');
        $manager = $this->getManager();

        // We retrieve comments
        $comments = $manager->getComment($post);

        $numberOfComments = count($comments);

        if ($numberOfComments > 0) {
            $numberOfComments = ($numberOfComments > 1) ?
                $numberOfComments.' Commentaires.' :
                $numberOfComments.' Commentaire.' ;
        } else {
            // If there is no comment.
            $numberOfComments = '0 Commentaire.' ;
        }

        // If the variable $_POST['comment'] exist
        // we control the commentary
        if (isset($_POST['comment'])) {
            // Sent comment control
            $comment = $this->container->get('CommentController');
            $this->flash = $comment->CommentControl();
        }
        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());

        //Retrieving the class that validates the token
        $token = $this->container->get('Token');

        // Adding parameters to return by the view
        $this->setParams([
            'comments' => $comments,
            'numberOfComments' => $numberOfComments,
            'token' => $token->getToken(),
            'post' => $post
        ]);

        // View recovery and display
        $this->getView();
        $this->send();
    }
}
