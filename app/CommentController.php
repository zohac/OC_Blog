<?php
namespace app;

use ZCFram\Controller;
use \app\Comment;

/**
 * Controller who manages the comments in the blog post page
 */
class CommentController extends Controller
{

    /**
     * Posted comment controller
     * @return array Returns flash messages
     */
    public function commentControl():Flash
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
                    'author' => $this->user->getUserInfo('id')]
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
                        'Commentaire en attente de validation. Merci '.$this->user->getUserInfo('pseudo').'!'
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
