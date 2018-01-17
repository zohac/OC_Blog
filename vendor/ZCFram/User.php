<?php
namespace ZCFram;

/**
 *  Represents a user.
 */
class User
{

    public function __construct()
    {
        \session_start();
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
    public function setAuthenticated($authenticated = true)
    {
        if (!is_bool($authenticated)) {
            $message = 'La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean';
            throw new \InvalidArgumentException($message);
        }
        $_SESSION['auth'] = $authenticated;
    }

    /**
     * Session variable authenticating a user role
     * @param string $role
     */
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
     */
    public function getRole()
    {
        $role = (isset($_SESSION['role'])) ? $_SESSION['role'] : false;
        if (!in_array($role, ['Subscriber', 'Administrator'])) {
            $message = 'La variable de session \'role\' n\'est pas conforme.';
            throw new \InvalidArgumentException($message);
        }
        return $role;
    }
}
