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

    function __construct()
    {
        // Test if a session is started.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // If a ticket exists
        if (isset($_SESSION['ticket'])) {
            // We're recording our ticket
            $this->ticket = $_SESSION['ticket'];
            unset($_SESSION['ticket']);
        } else {
            $response = Container::getHTTPResponse();

            // else we create our ticket
            $this->ticket = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

            $response->setCookie('ticket', $this->ticket, time()+300);
            $_SESSION['ticket'] =  $this->ticket;
        }
    }

    /**
     * Method for verifying the validity of a ticket
     * @param  string $ticket    The ticket to test
     * @return bool
     */
    public function isTicketValid():bool
    {
        $ticket = isset($_COOKIE['ticket'])? $_COOKIE['ticket']: null;

        // If both tickets match, return true
        if ($ticket == $this->ticket) {
            return true;
        }
        // else return false
        return false;
    }
}
