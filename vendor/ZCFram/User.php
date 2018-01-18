<?php
namespace ZCFram;

/**
 *  Represents a user.
 */
class User
{

    private $userInfo;

    public function __construct()
    {
        \session_start();
        if ($this->isAuthenticated()) {
            $this->userInfo = (isset($_SESSION['user']))? unserialize($_SESSION['user']): null;
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
    public function hydrateUser(array $userInfo)
    {
        if (!is_array($userInfo)) {
            $message = 'La valeur spécifiée à la méthode User::hydrateUser() n\'est pas conforme.';
            throw new \InvalidArgumentException($message);
        }
        var_dump($userInfo);
        $this->userInfo = $userInfo;
        $_SESSION['user'] = serialize($userInfo);
    }

    /**
     * Session variable authenticating a user role
     * @param string $role
     */
    public function getUserInfo()
    {
        return $this->userInfo;
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
            unset($_SESSION['role']);
            throw new \InvalidArgumentException($message);
        }
        return $role;
    }
}
