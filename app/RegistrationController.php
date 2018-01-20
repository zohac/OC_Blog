<?php
namespace app;

use ZCFram\Controller;
use ZCFram\Container;

/**
 *
 */
class RegistrationController extends Controller
{

    public function executeRegistration()
    {
        if (!empty($_POST)) {
            if ($this->samePassword($_POST['password'], $_POST['password2'])) {
                $Validator = Container::getValidator();

                $Validator->required('pseudo', 'text');
                $Validator->required('email', 'email');
                $Validator->required('password', 'password');
                $Validator->required('password2', 'password');

                if (!$Validator->hasError()) {
                    $params = $Validator->getParams();
                    $encryptedPassword = Container::getEncryption()->hash($params);
                    $manager = $this->getManager();

                    if (!$this->userExist($params['email']) OR !$this->userBanned($params['email'])) {
                        $answer = $manager->Registration($params['pseudo'], $params['email'], $encryptedPassword);

                        if ($answer === false) {
                            $this->setParams([
                                'errorRegistration'
                                => 'Une erreur est survenu lors de votre inscription, veuillez réessayer!'
                            ]);
                        } else {
                            $reponse = Container::getHTTPResponse();
                            $reponse->setStatus(301);
                            $reponse->redirection('/login');
                        }
                    }
                }
                $this->setParams($Validator->getParams());
            }
        }
        $this->getView();
        $this->send();
    }

    public function userExist(string $email):bool
    {
        $manager = $this->getManager();
        if ($manager->userExist($email)) {
            $this->setParams(['errorRegistration' => 'L\'utilisateur existe déjà!']);
            return true;
        } else {
            return false;
        }
    }

    public function userBanned(string $email):bool
    {
        $manager = $this->getManager();
        if ($manager->userBanned($email)) {
            $this->setParams(['errorRegistration' => 'L\'adresse email a été bannie!']);
            return true;
        } else {
            return false;
        }
    }

    public function samePassword(string $password1,string $password2):bool
    {
        if ($password1 != $password2) {
            $this->setParams(['errorRegistration' => 'Les deux mots de passe ne sont pas identique!']);
            return false;
        }
        return true;
    }
}
