<?php
namespace app;

use ZCFram\Controller;
use ZCFram\User;

/**
 * Class managing DB registration of a new user
 */
class RegistrationController extends Controller
{

    /**
     * Method registering in DB a new user
     * @return [Redirection to the login page]
     */
    public function executeRegistration()
    {
        //Retrieving the class that validates the token
        $token = $this->container->get('Token');

        // If variables exist in the post method
        if (!empty($_POST)) {
            // We're checking the validity of the token.
            if ($token->isTokenValid($_POST['token'])) {
                if ($this->samePassword($_POST['password'], $_POST['password2'])) {
                    //Retrieving the class that validates the data sent
                    $Validator = $this->container->get('Validator');
                    $Validator->required('pseudo', 'text');
                    $Validator->required('email', 'email');
                    $Validator->required('password', 'password');
                    $Validator->required('password2', 'password');

                    /*
                     * If the validator does not return an error,
                     * else adding error flash message
                     */
                    if (!$Validator->hasError()) {
                        // Recovering validator data
                        $params = $Validator->getParams();
                        // password hashing
                        $encryptedPassword = $this->container->get('Encryption')->hash($params);

                        $params = \array_merge(
                            $params,
                            ['password' => $encryptedPassword]
                        );
                        $user = new User($params);

                        // Recovery of the manager returned by the router
                        $manager = $this->getManager();

                        if ($this->isFirstRegistration()) {
                            $user->setRole('Administrator');
                            // User registration in DB
                            $result = $manager->registration($user);
                        } elseif (!$this->userExist($user) && !$this->userBanned($user)) {
                            // We check that the user does not exist, and that the email address is not banned
                            $user->setRole('Subscriber');
                            // User registration in DB
                            $result = $manager->registration($user);
                        } else {
                            $result = false;
                        }

                        // If the record failed, sends a flash message,
                        // otherwise redirection
                        if ($result === false) {
                            $this->flash->addFlash(
                                'danger',
                                'Une erreur est survenu lors de votre inscription, veuillez réessayer!'
                            );
                        } else {
                            $this->flash->addFlash(
                                'success',
                                'Vous êtes bien enregistré '. $params['pseudo'] .'! Veuillez Vous connecter.'
                            );
                            //Redirection to the login page
                            $reponse = $this->container->get('HTTPResponse');
                            $reponse->setStatus(301);
                            $reponse->redirection('/login');
                        }
                    } else {
                        // adding error flash message
                        foreach ($Validator->getError() as $key => $value) {
                            $this->flash->addFlash('danger', $value);
                        }
                    }
                }
            } else {
                $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'inscription.');
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
     * Check if a user exist in DB
     * @param  string $email
     * @return bool
     */
    public function userExist(User $user):bool
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();

        // Check if a user exist in DB
        if ($manager->userExist($user)) {
            // If a user exists, send a flash message
            $this->flash->addFlash('danger', 'L\'utilisateur existe déjà!');
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if a user email is banned
     * @param  string $email
     * @return bool
     */
    public function userBanned(User $user):bool
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();

        // Check if a user email is banned
        if ($manager->userBanned($user)) {
            // If a user is banned, send a flash message
            $this->flash->addFlash('danger', 'L\'adresse email a été bannie!');
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if both passwords are identical
     * @param  string $password1
     * @param  string $password2
     * @return bool
     */
    public function samePassword(string $password1, string $password2):bool
    {
        // Check if both passwords are identical
        if ($password1 != $password2) {
            // If the passwords are different, send a flash message
            $this->flash->addFlash('danger', 'Les deux mots de passe ne sont pas identique!');
            return false;
        }
        return true;
    }

    /**
     * Verify that it is the first user.
     * @return bool
     */
    public function isFirstRegistration():bool
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();

        // Check that it is the first user.
        if ($manager->firstRegistration()) {
            return true;
        }
        return false;
    }
}
