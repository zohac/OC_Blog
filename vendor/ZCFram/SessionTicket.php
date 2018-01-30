<?php
namespace ZCFram;

/**
 *
 */
class SessionTicket
{

    /**
     * A ticket
     * @var string $ticket
     */
    protected $ticket;

    /**
     * A ticket
     * @var string $ticket
     */
    protected $lifetime;

    public function __construct()
    {
        // Test if a session is started.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // If a ticket exists
        if (isset($_SESSION['ticket']) && isset($_SESSION['lifetimeTicket'])) {
            // We're recording our ticket
            $this->ticket = $_SESSION['ticket'];
            $this->lifetime = $_SESSION['lifetimeTicket'];
            unset($_SESSION['ticket']);
            unset($_SESSION['lifetimeTicket']);
        } else {
            $this->setTicket();
        }
    }

    /**
     *
     */
    public function setTicket()
    {
        // else we create our ticket
        $this->ticket = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $this->lifetime = time()+600;

        $response = Container::getHTTPResponse();
        $response->setCookie('ticket', $this->ticket, $this->lifetime, '/');

        $_SESSION['lifetimeTicket'] = $this->lifetime;
        $_SESSION['ticket'] =  $this->ticket;
    }

    /**
     * Method for verifying the validity of a ticket
     * @return bool
     */
    public function isTicketValid():bool
    {
        // Recovering the user-side ticket
        $ticket = isset($_COOKIE['ticket'])? $_COOKIE['ticket']: false;

        // If both tickets match, return true
        if ($ticket == $this->ticket && $this->lifetime > time()) {
            $this->setTicket();
            return true;
        }
        // else return false
        return false;
    }
}
