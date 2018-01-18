<?php
namespace app;

use ZCFram\Router;
use ZCFram\Controller;
use ZCFram\Container;

/**
 *
 */
class AdminController extends Controller
{

    public function __construct(Router $router)
    {
        parent::__construct($router);

        if (!$this->user->isAuthenticated()) {
            $reponse = Container::getHTTPResponse();
            $reponse->setStatus(301);
            $reponse->redirection('/login');
        }
    }

    public function executeDashboard()
    {
        $userInfo = $this->user->getUserInfo();

        $manager = $this->getManager();
        $listPosts = $manager->getList();
        $numberOfUsers = $manager->getNumberOfUsers();
        $numberOfPosts = $manager->getNumberOfPosts();
        $numberOfComments = $manager->getNumberOfComments();
        $listOfComments = $manager->getListOfComments();
        $myComments = $manager->getMyComments($userInfo['id']);
        $userInfo = $this->user->getUserInfo();

        $this->setParams(
            array_merge(
                ['listPosts' => $listPosts],
                $numberOfUsers,
                $numberOfPosts,
                $numberOfComments,
                ['listOfComment' => $listOfComments],
                ['myComments' => $myComments],
                ['userInfo' => $userInfo]
            )
        );

        $this->getView();
        $this->send();
    }

    public function AdminPost()
    {
        if (!empty($_POST)) {
            $Validator = Container::getValidator();

            $Validator->required('name', 'text');
            $Validator->required('email', 'email');
            $Validator->required('comments', 'text');

            if (!$Validator->hasError()) {
                $mailer = Container::getMailer();
                $message = Container::getSwiftMessage();

                $params = $Validator->getParams();
                // Give the message a subject
                $message->setBody('
                    De : '.$params['name'].'
                    Email : '.$params['email'].'
                    Content : '.$params['comments']);

                // Send the message
                $mailer->send($message);
            }
            $this->setParams($Validator->getParams());
        } else {
            $manager = $this->getManager();
            $Post = $manager->getPost($_GET['id']);

            $this->setParams($Post);
        }

        $this->getView();
        $this->send();
    }


    public function DeletePost()
    {
        $userInfo = $this->user->getUserInfo();

    if (!$this->user->isAuthenticated() && $userInfo['role'] == 'Administrator') {
    }
    }
}
