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
            switch ($this->user->getRole()) {
                case 'Administrator':
                    $manager = $this->getManager();
                    $listPosts = $manager->getList();
                    $numberOfUsers = $manager->getNumberOfUsers();
                    $numberOfPosts = $manager->getNumberOfPosts();

                    $this->setParams(
                        array_merge(
                            ['listPosts' => $listPosts],
                            $numberOfUsers,
                            $numberOfPosts
                        )
                    );
                    break;
                case 'Subscriber':
                    break;
            }
        }
        $this->getView();
        $this->send();
    }
}
