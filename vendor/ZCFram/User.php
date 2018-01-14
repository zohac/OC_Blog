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
            throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean');
        }
        $_SESSION['auth'] = $authenticated;
    }
}
