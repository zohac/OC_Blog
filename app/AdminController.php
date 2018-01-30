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

    public function __construct(Router $router, array $params)
    {
        parent::__construct($router, $params);

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
        // We verify that the user has the necessary rights
        if ($this->user->getUserInfo('role') == 'Administrator') {
            // Initializing the parameters to return to the view
            $this->setParams([
                    'author_id' => $this->user->getUserInfo('id'),
                    'author' => $this->user->getUserInfo('pseudo')
                ]);

            // Recovery of the manager returned by the router
            $manager = $this->getManager();

            // If variables exist in the post method
            if (!empty($_POST)) {
                //Retrieving the class that validates the token
                $token = Container::getToken();
                // We're checking the validity of the token.
                if ($token->isTokenValid($_POST['token'])) {
                    // If there is a file to upload
                    if (!empty($_FILES['upload'])) {
                        $id = $manager->getTheNewPostID();
                        $this->uploadFile($id);
                    }
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
                        // And insert new post in DB
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
                } else {
                    $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'enregistrement.');
                }
                // Adding flash message and parameters to return by the view
                $this->setParams($this->flash->getFlash());
                $this->setParams($params);
            }
            //Retrieving the class that validates the token
            $token = $token->getToken();
            // Adding token to the parameters to return by the view
            $this->setParams(['token' => $token]);

            // Adding flash message and parameters to return by the view
            $this->setParams($this->flash->getFlash());

            // Name of the view to return
            $this->setView('adminpost');
            // View recovery and display
            $this->getView();
            $this->send();
        }
    }

    /**
     * Update a post
     */
    public function executeUpdate()
    {
        // We verify that the user has the necessary rights
        if ($this->user->getUserInfo('role') == 'Administrator') {
            // Recovery of the manager returned by the router
            $manager = $this->getManager();

            if (isset($_GET['id'])) {
                // Retrieving the id of the post to delete and convert to integer
                $id = (int)$_GET['id'];

                // Displays a delete confirmation message
                $this->setParams(['deletePost' => $id]);
            }

            // If variables exist in the post method
            // and the variable 'id' exist
            // else recovery of the post in DB for display of the completed form
            if (!empty($_POST) && $id) {
                //Retrieving the class that validates the token
                $token = Container::getToken();
                // We're checking the validity of the token.
                if ($token->isTokenValid($_POST['token'])) {
                    // If there is a file to upload
                    if (!empty($_FILES['upload'])) {
                        $this->uploadFile($id);
                    }
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
                } else {
                    $this->flash->addFlash('danger', 'Une erreur est survenu lors de la mise à jour.');
                }
            } elseif (isset($_GET['id'])) {
                    $Post = $manager->getPost($_GET['id']);
                    $this->setParams($Post);
            }
            //Retrieving the class that validates the token
            $token = $token->getToken();
            // Adding token to the parameters to return by the view
            $this->setParams(['token' => $token]);

            // Adding flash message and parameters to return by the view
            $this->setParams($this->flash->getFlash());

            // Name of the view to return
            $this->setView('adminpost');

            // View recovery and display
            $this->getView();
            $this->send();
        }
    }

    /**
     * Method to delete a post
     * @return [Redirection to the Dashboard]
     */
    public function executeDeletePost()
    {
        // We verify that the user has the necessary rights
        if ($this->user->getUserInfo('role') == 'Administrator') {
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
                //Retrieving the class that validates the token
                $token = Container::getToken();
                // We're checking the validity of the token.
                if ($token->isTokenValid($_POST['token'])) {
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
                        $this->flash->addFlash('danger', 'Une erreur et survenue, veuillez réessayer.');
                    }
                } else {
                    $this->flash->addFlash('danger', 'Une erreur et survenue, veuillez réessayer.');
                }
            }
            if (isset($_POST['No']) or isset($result)) {
                // Redirection on the user page
                $reponse = Container::getHTTPResponse();
                $reponse->setStatus(301);
                $reponse->redirection('/admin');
            }
            //Retrieving the class that validates the token
            $token = $token->getToken();
            // Adding token to the parameters to return by the view
            $this->setParams(['token' => $token]);

            // View recovery and display
            $this->getView();
            $this->send();
        }
    }

    /**
     * Method for validating a comment
     * @return [Redirection to the Dashboard]
     */
    public function executeValidComment()
    {
        // We verify that the user has the necessary rights
        if ($this->user->getUserInfo('role') == 'Administrator') {
            // admin dashboard recovery
            $this->getAdminDashboard();
            $this->setView('dashboard');

            // If the $_Get['id'] variable exists, we make sure it is an integer,
            // otherwise we return false.
            if (isset($_GET['id'])) {
                // Retrieving the id of the post to delete and convert to integer
                $id = (int)$_GET['id'];

                // Displays a delete confirmation message
                $this->setParams(['validComment' => $id]);
            } else {
                $id = false;
            }


            // If variables exist in the post method
            // and the variable 'Yes' existe
            if (!empty($_POST) && isset($_POST['Yes'])) {
                //Retrieving the class that validates the token
                $token = Container::getToken();
                // We're checking the validity of the token.
                if ($token->isTokenValid($_POST['token'])) {
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
                        $result = $manager->validComment($params['id']);

                        // Adding a flash message if successful or unsuccessful
                        if ($result !== false) {
                            $this->flash->addFlash('success', 'Le commentaire est bien validé.');
                        } else {
                            $this->fash->addFlash(
                                'danger',
                                'Une erreur est survenu lors de la validation du commentaire.'
                            );
                        }
                    } else {
                        $this->flash->addFlash('danger', 'Une erreur et survenue, veuillez réessayer.');
                    }
                } else {
                    $this->flash->addFlash('danger', 'Une erreur et survenue, veuillez réessayer.');
                }
            }
            if (isset($_POST['No']) or isset($result)) {
                // Redirection on the user page
                $reponse = Container::getHTTPResponse();
                $reponse->setStatus(301);
                $reponse->redirection('/admin');
            }
            //Retrieving the class that validates the token
            $token = $token->getToken();
            // Adding token to the parameters to return by the view
            $this->setParams(['token' => $token]);

            // View recovery and display
            $this->getView();
            $this->send();
        }
    }

    /**
     * Method for deleting a comment
     * @return [Redirection to the Dashboard]
     */
    public function executeDeleteComment()
    {
        // If the $_Get['id'] variable exists, we make sure it is an integer,
        // otherwise we return false.
        if (isset($_GET['id'])) {
            // Retrieving the id of the post to delete and convert to integer
            $id = (int)$_GET['id'];

            // Displays a delete confirmation message
            $this->setParams(['deleteComment' => $id]);
        } else {
            $id = false;
        }

        // Recovery of the manager returned by the router
        $manager = $this->getManager();

        // We verify that the user has the necessary rights
        if ($this->user->getUserInfo('role') == 'Administrator'
        or
        $manager->isWrittenByTheUser($id, $this->user->getUserInfo('id'))
        ) {
            // admin dashboard recovery
            $this->getAdminDashboard();
            $this->setView('dashboard');

            // If variables exist in the post method
            // and the variable 'Yes' existe
            if (!empty($_POST) && isset($_POST['Yes'])) {
                //Retrieving the class that validates the token
                $token = Container::getToken();
                // We're checking the validity of the token.
                if ($token->isTokenValid($_POST['token'])) {
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
                        $result = $manager->deleteComment($params['id']);

                        // Adding a flash message if successful or unsuccessful
                        if ($result !== false) {
                            $this->flash->addFlash('success', 'Le commentaire est bien supprimé.');
                        } else {
                            $this->fash->addFlash(
                                'danger',
                                'Une erreur est survenu lors de la suppression du commentaire.'
                            );
                        }
                    } else {
                        $this->flash->addFlash('danger', 'Une erreur et survenue, veuillez réessayer.');
                    }
                } else {
                    $this->flash->addFlash('danger', 'Une erreur et survenue, veuillez réessayer.');
                }
            }
            if (isset($_POST['No']) or isset($result)) {
                // Redirection on the user page
                $reponse = Container::getHTTPResponse();
                $reponse->setStatus(301);
                $reponse->redirection('/admin');
            }
            //Retrieving the class that validates the token
            $token = $token->getToken();
            // Adding token to the parameters to return by the view
            $this->setParams(['token' => $token]);

            // View recovery and display
            $this->getView();
            $this->send();
        }
    }

    /**
     * [uploadFile description]
     * @param  int    $id [description]
     * @return [type]     [description]
     */
    public function uploadFile(int $id)
    {

        $error = false;

        // We define our constants
        $newName = 'blog-'.$id;
        $path = __DIR__.'/../web/upload';
        $legalExtensions = array("jpg", "png", "gif");
        $legalSize = "4000000"; // 4 MO

        // We're retrieving information from the file
        $file = $_FILES['upload'];
        $actualName = $file['tmp_name'];
        $actualSize = $file['size'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Make sure the file is not empty
        if ($actualName === 0 || $actualSize == 0) {
            $this->flash->addFlash('danger', 'Le fichier est vide.');
            $error = true;
        }

        // We verify that a file with the same name is not present on the server
        if (file_exists($path.'/'.$newName.'.'.$extension)) {
            $this->flash->addFlash('danger', 'Un fichier portant le même nom est présent sur le serveur.');
            $error = true;
        }

        // We conduct our regulatory audits
        if (!$error) {
            if ($actualSize < $legalSize) {
                if (in_array($extension, $legalExtensions)) {
                    // Upload the file
                    move_uploaded_file($actualName, $path.'/'.$newName.'.'.$extension);
                }
            }
        } else {
            // The file is deleted from the server
            @unlink($path.'/'.$newName.'.'.$extension);
            $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'upload de l\'image.');
        }
    }
}
