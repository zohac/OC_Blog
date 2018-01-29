<?php
namespace ZCFram;

/**
 * Class generating and verifying a Token
 * Prevent CRSF flaws
 */
class Token
{

    /**
     * A token
     * @var string $token
     */
    protected $token;

    function __construct()
    {
        // Test if a session is started.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // If a token exists
        if (isset($_SESSION['token'])) {
            // We're recording our token
            $this->token = $_SESSION['token'];
            unset($_SESSION['token']);
        } else {
            // else we create our token
            $this->token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        }
    }

    /**
     * Return the registered token
     * @return string   The token
     */
    public function getToken():string
    {
        $_SESSION['token'] =  $this->token;
        return $this->token;
    }

    /**
     * Method for verifying the validity of a token
     * @param  string $token    The token to test
     * @return bool
     */
    public function isTokenValid(string $token):bool
    {
        // If both tokens match, return true
        if ($token == $this->token) {
            return true;
        }
        // else return false
        return false;
    }
}
