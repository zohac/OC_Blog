<?php
namespace app\Controller;

use ZCFram\Controller;

/**
 * Class to manage user authentication
 */
class LoginController extends Controller
{

    /**
     * Methode to manage user authentication
     * @return [Redirect on success]
     */
    public function executeLogin()
    {
        //Retrieving the class that validates the token
        $token = $this->container->get('Token');
        // If variables exist in the post method
        if (!empty($_POST)) {
            // We're checking the validity of the token.
            if ($token->isTokenValid($_POST['token'])) {
                //Retrieving the class that validates the data sent
                $Validator = $this->container->get('Validator');
                $Validator->required('email', 'email');
                $Validator->required('password', 'password');

                /*
                 * If the validator does not return an error,
                 * else adding error flash message
                 */
                if (!$Validator->hasError()) {
                    // Recovering validator data
                    $params = $Validator->getParams();

                    // password hashing
                    $encryptedPassword = $this->container->get('Encryption')->hash($params);

                    // Recovery of the manager returned by the router
                    // And check if the user is registered in DB
                    $manager = $this->getManager();
                    $user = $manager->getUser($params['email'], $encryptedPassword);

                    // If the user doesn't exist
                    // Add a flash message
                    if ($user === false) {
                        $this->flash->addFlash('danger', 'Il existe une erreur dans le couple email/Mot de passe!');
                    } else {
                        // Authenticate the user and hydrate the User class
                        $user->setAuthenticated();

                        //Redirection to the admin page
                        $reponse = $this->container->get('HTTPResponse');
                        $reponse->setStatus(301);
                        $reponse->redirection('/admin');
                    }
                } else {
                    // adding error flash message
                    foreach ($Validator->getError() as $key => $value) {
                        $this->flash->addFlash('danger', $value);
                    }
                }
            } else {
                $this->flash->addFlash('danger', 'Une erreur est survenu.');
            }
        }
        //Retrieving the class that validates the token
        $token = $token->getToken();
        // Adding token to the parameters to return by the view
        $this->setParams(['token' => $token]);

        // Flash message retrieval
        $this->setParams($this->flash->getFlash());

        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Methode to manage logout the user
     * @return [Redirecting the user to the index page]
     */
    public function executeLogout()
    {
        // We cancel the authentication
        $user = $this->container->get('User');
        $user->setAuthenticated(false);

        // Redirection to the index page
        $reponse = $this->container->get('HTTPResponse');
        $reponse->setStatus(301);
        $reponse->redirection('/');
    }
}
