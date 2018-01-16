<?php
namespace app;

use ZCFram\Controller;
use ZCFram\Container;

/**
 *
 */
class LoginController extends Controller
{

    public function executeLogin()
    {
        if (!empty($_POST)) {
            $Validator = Container::getValidator();

            $Validator->required('email', 'email');
            $Validator->required('password', 'password');

            if (!$Validator->hasError()) {
                $params = $Validator->getParams();

                $encryptedPassword = Container::getEncryption()->hash($params);
                $manager = $this->getManager();
                $user = $manager->getUser($params['email'], $encryptedPassword);

                if ($user === false) {
                    $this->setParams(['errorLogin' => 'Il existe une erreur dans le couple email/Mot de passe!']);
                } else {
                    $this->user->setAuthenticated();

                    $reponse = Container::getHTTPResponse();
                    $reponse->setStatus(301);
                    $reponse->redirection('/admin');
                }
            }
            $this->setParams($Validator->getParams());
        }

        $this->getView();
        $this->send();
    }
}
