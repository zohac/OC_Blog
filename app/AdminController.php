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
            $this->getView();
            $this->send();
        }
    }
}
