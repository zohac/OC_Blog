<?php
namespace app;

use ZCFram\Controller;
use ZCFram\Container;

/**
 * Controller who manages the comments in the blog post page
 */
class CommentController extends Controller
{

    /**
     * [commentControl description]
     * @return [type] [description]
     */
    public function commentControl()
    {
        //Retrieving the class that validates the token
        $token = Container::getToken();
        if ($token->isTokenValid($_POST['token'])) {
            // we make sure that the variable $_GET['id'] is an integer
            $id = (int)$_GET['id'];

            //Retrieving the class that validates the data sent
            $Validator = Container::getValidator();
            $Validator->required('comment', 'text');

            // If the validator does not return an error,
            // else adding error flash message
            //
            if (!$Validator->hasError()) {
                // Recovery of validated data
                $params = array_merge(
                    $Validator->getParams(),
                    ['post_id' => $id,
                    'author_id' => $this->user->getUserInfo('id')]
                );

                $this->setManager('Comment');
                $manager = $this->getManager();
                $result = $manager->insertComment($params);

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
                foreach ($Validator->getError() as $key => $value) {
                    $this->flash->addFlash('danger', $value);
                }
            }
        } else {
            $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'enregistrement du commentaire.');
        }
    }
}
