<?php
namespace app;

use ZCFram\Router;
use ZCFram\Controller;
use ZCFram\Container;

/**
 * Class managing the actions of the administration panel
 */
class AdminController extends Controller
{

    public function __construct(Router $router)
    {
        parent::__construct($router);

        // Test if the user is authenticated
        if (!$this->user->isAuthenticated()) {
            // If it is not authenticated, redirection to the login page
            $reponse = Container::getHTTPResponse();
            $reponse->setStatus(301);
            $reponse->redirection('/login');
        } else {
            // Retrieving user information
            $this->setParams(['userInfo' => [
                    'pseudo' => $this->user->getUserInfo('pseudo'),
                    'email' => $this->user->getUserInfo('email')
                ]]);
        }
    }

    /**
     * Send the view of the Dashboard
     * @return [Send the view of the Dashboard]
     */
    public function executeDashboard()
    {
        // Retrieving the role of the user
        $role = $this->user->getUserInfo('role');

        // Depending on the role of the user,
        // we recover the dashboard 'Administrator', or 'user'
        switch ($role) {
            case 'Administrator':
                $this->getAdminDashboard();
                break;

            default:
                $this->getUserDashboard();
                break;
        }
        // Flash message retrieval
        $this->setParams($this->flash->getFlash());

        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Recover the info for the dashboard 'Administrator'
     */
    public function getAdminDashboard()
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();

        // Retrieving info for displaying the dashboard
        $listPosts = $manager->getList();
        $numberOfUsers = $manager->getNumberOfUsers();
        $numberOfPosts = $manager->getNumberOfPosts();
        $numberOfComments = $manager->getNumberOfComments();
        $listOfComments = $manager->getListOfComments();
        $myComments = $manager->getMyComments($this->user->getUserInfo('id'));

        // Initializing the parameters to return to the view
        $this->setParams(
            array_merge(
                ['listPosts' => $listPosts],
                $numberOfUsers,
                $numberOfPosts,
                $numberOfComments,
                ['listOfComment' => $listOfComments],
                ['myComments' => $myComments],
                ['userRole' => $this->user->getUserInfo('role')]
            )
        );
    }

    /**
     * Recover the info for the dashboard 'user'
     */
    public function getUserDashboard()
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();

        // Retrieving info for displaying the dashboard
        $myComments = $manager->getMyComments($this->user->getUserInfo('id'));

        // Initializing the parameters to return to the view
        $this->setParams(
            array_merge(
                ['myComments' => $myComments],
                ['userRole' => $this->user->getUserInfo('role')]
            )
        );
    }

    /**
     * Method to insert a new post in DB
     */
    public function executeInsert()
    {
        // Initializing the parameters to return to the view
        $this->setParams([
                'author_id' => $this->user->getUserInfo('id'),
                'author' => $this->user->getUserInfo('pseudo')
            ]);

        // If variables exist in the post method
        if (!empty($_POST)) {
            //Retrieving the class that validates the data sent
            $Validator = Container::getValidator();
            $Validator->required('status', 'text');
            $Validator->required('title', 'text');
            $Validator->check('post', 'text');

            // Creating a parameter table
            // 1. For sending in DB
            // 2. For viewing the view
            $params = \array_merge(
                $Validator->getParams(),
                [
                    'author_id' => $this->user->getUserInfo('id'),
                    'creationDate' => date('Y-m-d'),
                    'modificationDate' => date('Y-m-d')
                ]
            );

            /*
             * If the validator does not return an error,
             * else adding error flash message
             */
            if (!$Validator->hasError()) {
                // Recovery of the manager returned by the router
                // And insert new post in DB
                $manager = $this->getManager();
                $result = $manager->insertPost($params);

                // Adding a flash message if successful or unsuccessful
                if ($result !== false) {
                    $this->flash->addFlash('success', 'Nouvel article enregistré.');
                } else {
                    $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'enregistrement.');
                }

                // redirection to the admin page
                $reponse = Container::getHTTPResponse();
                $reponse->setStatus(301);
                $reponse->redirection('/admin');
            } else {
                // adding error flash message
                foreach ($Validator->getError() as $key => $value) {
                    $this->flash->addFlash('danger', $value);
                }
            }
            // Adding flash message and parameters to return by the view
            $this->setParams($this->flash->getFlash());
            $this->setParams($params);
        }
        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());

        // Name of the view to return
        $this->setView('adminpost');
        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Update a post
     */
    public function executeUpdate()
    {
        // Recovery of the manager returned by the router
        $manager = $this->getManager();

        // If variables exist in the post method
        // and the variable 'id' exist
        // else recovery of the post in DB for display of the completed form
        if (!empty($_POST) && isset($_GET['id'])) {
            //Retrieving the class that validates the data sent
            $Validator = Container::getValidator();
            $Validator->required('status', 'text');
            $Validator->required('title', 'text');
            $Validator->check('post', 'text');

            /*
             * If the validator does not return an error,
             * else adding error flash message
             */
            if (!$Validator->hasError()) {
                // Creating a parameter table
                // 1. For sending in DB
                $params = \array_merge($Validator->getParams(), ['id' => $_GET['id']]);

                // Update the post
                $result = $manager->updatePost($params);

                // Adding a flash message if successful or unsuccessful
                if ($result !== false) {
                    $this->flash->addFlash('success', 'Article mis à jour.');
                } else {
                    $this->flash->addFlash('danger', 'Une erreur est survenu lors de la mise à jour.');
                }

                // Recovery the Post in DB
                $Post = $manager->getPost($_GET['id']);
                $this->setParams($Post);
            }
        } elseif (isset($_GET['id'])) {
                $Post = $manager->getPost($_GET['id']);
                $this->setParams($Post);
        }
        // Adding flash message and parameters to return by the view
        $this->setParams($this->flash->getFlash());

        // Name of the view to return
        $this->setView('adminpost');

        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Method to delete a post
     * @return [Redirection to the Dashboard]
     */
    public function executeDeletePost()
    {
        // admin dashboard recovery
        $this->getAdminDashboard();
        $this->setView('dashboard');

        if (isset($_GET['id'])) {
            // Retrieving the id of the post to delete and convert to integer
            $id = (int)$_GET['id'];

            // Displays a delete confirmation message
            $this->setParams(['deletePost' => $id]);
        }

        // If variables exist in the post method
        // and the variable 'Yes' existe
        if (!empty($_POST) && isset($_POST['Yes'])) {
            //Retrieving the class that validates the data sent
            $Validator = Container::getValidator();
            $Validator->check('id', 'integer');

            // Recovery of validated data
            $params = $Validator->getParams();

            /*
             * If the validator does not return an error,
             * and the id sent by the POST method and identical to the id of the GET method
             * Otherwise sending a flash message in case of error
             */
            if (!$Validator->hasError() && $params['id'] == $id) {
                // Recovery of the manager returned by the router
                $manager = $this->getManager();
                $result = $manager->deletePost($params['id']);

                // Adding a flash message if successful or unsuccessful
                if ($result !== false) {
                    $this->flash->addFlash('success', 'Le post est bien supprimé.');
                } else {
                    $this->fash->addFlash('danger', 'Une erreur est survenu lors de la supression du post.');
                }
            } else {
                $this->flash->addFlash('danger', 'Une erreur et survenue, veuillez Réessyer.');
            }
        }
        if (isset($_POST['No']) or isset($result)) {
            // Redirection on the user page
            $reponse = Container::getHTTPResponse();
            $reponse->setStatus(301);
            $reponse->redirection('/admin');
        }
        // View recovery and display
        $this->getView();
        $this->send();
    }
}
