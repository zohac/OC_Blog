<?php
namespace app\Entity;

use \ZCFram\Hydrator;

/**
 * Class representing a user
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
            // Hydrate the class
            $this->hydrate($data);
        } elseif ($this->isAuthenticated() && isset($_SESSION['user'])) {
            // Hydrate the class
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

    /**
     * Set the Password of the user
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * Set the Role of the user
     * @param string $role
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }

    /**
     * Set the Status of the user
     * @param string $passstatusword
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * Get the user id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get the pseudo of the user
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Get the email of the user
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get The password of the user
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the role of the user
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get the status of the user
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
