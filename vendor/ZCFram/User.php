<?php
namespace ZCFram;

/**
 *  Class representing a user.
 */
class User
{
    /**
     * Represents a user.
     * @var $userInfo
     */
    private $userInfo;

    public function __construct()
    {
        // Test if a session is started.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // If the user is authenticated,
        // its information stored as a session variable is retrieved.
        if ($this->isAuthenticated() && isset($_SESSION['user'])) {
            $this->userInfo = $_SESSION['user'];
        } else {
            // Otherwise, the authentication is deleted.
            $this->setAuthenticated(false);
        }
    }

    /**
     * Check the auth session variable
     * @return boolean
     */
    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    /**
     * Session variable authenticating a user
     * @param boolean $authenticated
     */
    public function setAuthenticated(bool $authenticated = true)
    {
        // If the variable is not a boolean, an exception is called.
        if (!is_bool($authenticated)) {
            $message = 'La valeur spécifiée à la méthode User::setAuthenticated() doit être un booléen';
            throw new \InvalidArgumentException($message);
        }
        // Authentication is defined in a session variable
        $_SESSION['auth'] = $authenticated;
    }

    /**
     * Session variable authenticating a user role
     * @param string $role
     */
    public function hydrateUser(array $userInfo)
    {
        // TODO : Refaire l'hydratation de l'utilisateur
        //TODO
        if (!is_array($userInfo)) {
            $message = 'La valeur spécifiée à la méthode User::hydrateUser() n\'est pas conforme.';
            throw new \InvalidArgumentException($message);
        }
        $this->userInfo = $userInfo;
        $_SESSION['user'] = $userInfo;
    }

    /**
     * Retrieves the info of the current user.
     * @param  string $value Information to recover
     * @return string        The requested info
     */
    public function getUserInfo(string $value):string
    {
        // If the variable is not defined, an exception is called.
        if (!in_array($value, ['id', 'pseudo', 'email', 'role'])) {
            $message = 'La valeur spécifiée à la méthode User::getUserInfo() n\'est pas conforme.';
            throw new \InvalidArgumentException($message);
        }
        // Returns the requested information.
        return $this->userInfo[$value];
    }

    /**
     * Session variable authenticating a user role
     * @param string $role
     *
    public function setRole(string $role = 'Subscriber')
    {
        if (!in_array($role, ['Subscriber', 'Administrator'])) {
            $message = 'La valeur spécifiée à la méthode User::setRole() n\'est pas conforme.';
            throw new \InvalidArgumentException($message);
        }
        $_SESSION['role'] = $role;
    }

    /**
     * Session variable authenticating a user role
     * @return string $role
     *
    public function getRole()
    {
        $role = (isset($_SESSION['role'])) ? $_SESSION['role'] : false;
        if (!in_array($role, ['Subscriber', 'Administrator'])) {
            $message = 'La variable de session \'role\' n\'est pas conforme.';
            unset($_SESSION['role']);
            throw new \InvalidArgumentException($message);
        }
        return $role;
    }*/
}
