<?php
namespace app;

use ZCFram\Controller;
use ZCFram\Container;

/**
 *
 */
class AdminController extends Controller
{

    public function executeDashboard()
    {
        if (!$this->user->isAuthenticated()) {
            $reponse = Container::getHTTPResponse();
            $reponse->setStatus(301);
            $reponse->redirection('/login');
        } else {
            # code...
        }
    }

    public function executeLogin()
    {
        if (!empty($_POST)) {
            $Validator = Container::getValidator();

            $Validator->required('email', 'email');
            $Validator->required('password', 'text');

            if (!$Validator->hasError()) {
                echo 'no error';
            } else {
                echo 'error';
                $this->setParams($Validator->getParams());
                //$this->setView('alertControlForm');
            }
        }
        $this->getView();
        $this->send();
    }
}
