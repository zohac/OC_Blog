<?php
namespace ZCFram;

/**
 * Class to protect a session with a ticket system
 */
class SessionTicket
{
    /**
     * An instance of HTTPResponse
     * @var object HTTPResponse
     */
    protected $response;

    /**
     * A ticket
     * @var string $ticket
     */
    protected $ticket;

    /**
     * The lifetime of a session
     * @var string $lifetime
     */
    protected $lifetime;

    /**
     * An instance of the DIC
     * @var object DIC
     */
    protected $container;

    public function __construct(HTTPResponse $response)
    {
        $this->response = $response;

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
     * Ticket creation
     * @return cookie $_COOKIE
     */
    public function setTicket()
    {
        // we create our ticket
        $this->ticket = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        // Set ticket lifetime (5min)
        $this->lifetime = time()+600;

        // Send the ticket as a cookie
        $this->response->setCookie('ticket', $this->ticket, $this->lifetime, '/');

        // Creating the corresponding session variables
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
