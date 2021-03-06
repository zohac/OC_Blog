<?php
namespace app\Controller;

use \ZCFram\Controller;
use \app\Entity\Comment;

/**
 * Controller who manages the comments in the blog post page
 */
class CommentController extends Controller
{
    /**
     * Represents a user.
     * @var object user
     */
    protected $user;

    /**
     * [__construct description]
     * @param \ZCFram\DIC $container [description]
     */
    public function __construct(\ZCFram\Router $router, \ZCFram\DIC $container)
    {
        parent::__construct($router);

        // Register of the container through the parameters
        $this->container = $container;

        // Get Message flash
        $this->flash = $container->get('Flash');

        // Register the user
        $this->user = $this->container->get('User');

        // Get the user
        $this->setParams(['user' => $this->user]);
    }

    /**
     * Posted comment controller
     * @return array Returns flash messages
     */
    public function commentControl()
    {
        //Retrieving the class that validates the token
        $token = $this->container->get('Token');
        if ($token->isTokenValid($_POST['token'])) {
            // we make sure that the variable $_GET['id'] is an integer
            $id = (int)$_GET['id'];

            //Retrieving the class that validates the data sent
            $Validator = $this->container->get('Validator');
            $Validator->required('comment', 'text');

            // If the validator does not return an error,
            // else adding error flash message
            //
            if (!$Validator->hasError()) {
                // Recovery of validated data
                $params = array_merge(
                    $Validator->getParams(),
                    ['idPost' => $id,
                    'author' => $this->user->getUserId()]
                );

                $comment = new Comment($params);

                // Retrieve the manager and insert comments in DB
                $this->setManager('Comment');
                $manager = $this->getManager();
                $result = $manager->insertComment($comment);

                // Adding a flash message if successful or unsuccessful
                if ($result > 0) {
                    $this->flash->addFlash(
                        'success',
                        'Commentaire en attente de validation. Merci '.$this->user->getPseudo().'!'
                    );
                } else {
                    $this->flash->addFlash(
                        'danger',
                        'Une erreur est survenu lors de l\'enregistrement du commentaire.'
                    );
                }
            } else {
                // For each error returned by the validator, a flash message is displayed.
                foreach ($Validator->getError() as $key => $value) {
                    $this->flash->addFlash('danger', $value);
                }
            }
        } else {
            // If the token is not valid, a flash message is displayed.
            $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'enregistrement du commentaire.');
        }
        // Returns flash messages
        return $this->flash;
    }
}
