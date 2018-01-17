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
            if ($_POST['password'] === $_POST['password2']) {
                $Validator = Container::getValidator();

                $Validator->required('pseudo', 'text');
                $Validator->required('email', 'email');
                $Validator->required('password', 'password');
                $Validator->required('password2', 'password');

                if (!$Validator->hasError()) {
                    $params = $Validator->getParams();
                    $encryptedPassword = Container::getEncryption()->hash($params);
                    $manager = $this->getManager();
                    $answer = $manager->Registration($params['pseudo'], $params['email'], $encryptedPassword);

                    if ($answer === false) {
                        $this->setParams([
                            'errorRegistration'
                            => 'Une erreur est survenu lors de votre inscription, veuillez réessayer!'
                        ]);
                    } else {
                        $this->user->setAuthenticated();

                        $reponse = Container::getHTTPResponse();
                        $reponse->setStatus(301);
                        $reponse->redirection('/admin');
                    }
                }
                $this->setParams($Validator->getParams());
            } else {
                $this->setParams(['errorRegistration' => 'Les deux mots de passe ne sont pas identique!']);
            }
        }
        $this->getView();
        $this->send();
    }
}
