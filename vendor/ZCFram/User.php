<?php
namespace ZCFram;

/**
 *  Class representing a user.
 */
class User
{
    use Hydrator;

    /**
     * The id user
     * @var int
     */
    private $userId;

    /**
     * The pseudo of the user
     * @var string
     */
    private $pseudo;

    /**
     * The email of the user
     * @var string
     */
    private $email;

    /**
     * The password of the user
     * @var string
     */
    private $password;

    /**
     * The role of the user
     * @var string
     */
    private $role;

    /**
     * The status of the user
     * @var string
     */
    private $status;

    public function __construct(array $data = [])
    {
        // Test if a session is started.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // If the user is authenticated,
        // its information stored as a session variable is retrieved.
        if ($data) {
            //
            $this->hydrate($data);
        } elseif ($this->isAuthenticated() && isset($_SESSION['user'])) {
            //
            $this->hydrate($_SESSION['user']);
            $_SESSION['user'] = $this;
        } else {
            // Otherwise, the authentication is deleted.
            $this->setAuthenticated(false);
            unset($_SESSION['user']);
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
        $_SESSION['user'] = $this;
        $_SESSION['auth'] = $authenticated;
    }

    /**
     * Set the User id
     * @param int $id
     */
    public function setUserId(int $id)
    {
        $this->userId = $id;
    }

    /**
     * Set the pseudo User
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Set the email of the user
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setRole(string $role)
    {
        $this->role = $role;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
