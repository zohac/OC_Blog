<?php
namespace app;

use ZCFram\Controller;

/**
 * Class that manages users by administrators
 */
class UserController extends Controller
{

    /**
     * Uses the parent constructor and adds the user role check
     * @param Router
     */
    public function __construct(\ZCFram\DIC $container, array $params)
    {
        parent::__construct($container, $params);

        // Check the role of the user.
        if ($this->user->getRole() != 'Administrator') {
            // If it is not an administrator, redirection to the admin page
            $reponse = $this->container->get('HTTPResponse');
            $reponse->setStatus(301);
            $reponse->redirection('/admin');
        }
    }

    /**
     * Retrieves the list of users, and displays the view defined by the router
     * @return [Send the view]
     */
    public function executeListUser()
    {
        // Retrieves the list of users
        $this->getListUser();

        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());

        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Retrieves the list of users
     */
    public function getListUser()
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();
        // Retrieves the list of users
        $listUser = $manager->getListUser();

        // Add the user list to the view parameter
        $this->setParams(['listUser' => $listUser]);
    }

    /**
     * Method to delete a user
     * @return [Send the view]
     */
    public function executeDeleteUser()
    {
        if (isset($_GET['id'])) {
            // Retrieving the id of the post to delete and convert to integer
            $id = (int)$_GET['id'];

            // Displays a delete confirmation message
            $this->setParams(['deleteUser' => $id]);
        }

        //Retrieving the class that validates the token
        $token = $this->container->get('Token');

        // If variables exist in the post method
        // and the variable 'Yes' existe
        if (!empty($_POST) && isset($_POST['Yes'])) {
            if (!$token->isTokenValid($_POST['token'])) {
                //Retrieving the class that validates the data sent
                $Validator = $this->container->get('Validator');
                $Validator->check('id', 'integer');

                //Recovery of validated data
                $params = $Validator->getParams();

                /*
                 * If the validator does not return an error,
                 * and the id sent by the POST method and identical to the id of the GET method
                 * Otherwise sending a flash message in case of error
                 */
                if (!$Validator->hasError() && $params['id'] == $id) {
                    // Recovery of the manager returned by the router
                    $manager = $this->getManager();
                    $result = $manager->deleteUser($id);

                    // Adding a flash message if successful or unsuccessful
                    if ($result !== false) {
                        $this->flash->addFlash('success', 'L\'utilisateur est bien supprimé.');
                    } else {
                        $this->fash->addFlash(
                            'danger',
                            'Une erreur est survenu lors de la supression de l\'utilisateur.'
                        );
                    }
                } else {
                    $this->flash->addFlash('danger', 'Une erreur et survenue, Veuillez Réessyer.');
                }
            } else {
                $this->flash->addFlash('danger', 'Une erreur et survenue, Veuillez Réessyer.');
            }
        }

        // If variables exist in the post method
        // and the variable 'No' OR '$result' exist
        if (isset($_POST['No']) || isset($result)) {
            // Redirection on the user page
            $reponse = $this->container->get('HTTPResponse');
            $reponse->setStatus(301);
            $reponse->redirection('/admin/user.html');
        }
        //Retrieving the class that validates the token
        $token = $token->getToken();
        // Adding token to the parameters to return by the view
        $this->setParams(['token' => $token]);

        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());

        // Retrieves the list of users
        $this->getListUser();

        // Change the default view
        $this->setView('listuser');

        // View recovery and display
        $this->getView();
        $this->send();
    }
}
