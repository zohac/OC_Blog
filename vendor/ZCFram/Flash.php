<?php
namespace ZCFram;

/**
 * Management of flash messages
 */
class Flash
{

    /**
     * Table representing all flash messages
     * @var array
     */
    private $flashMessage = [
        'success' => [],
        'info' => [],
        'warning' => [],
        'danger' => []
    ];

    /**
     * Table representing the different type of flash message.
     * @var array
     */
    private $flashType = ['success', 'info', 'warning', 'danger'];

    public function __construct()
    {
        //Test if a session is started.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Test if flash messages exist
        if (isset($_SESSION['flash'])) {
            $this->flashMessage = $_SESSION['flash'];
            unset($_SESSION['flash']);
        }
    }

    /**
     * Method returning flash messages.
     * @return array $flash     Table containing all flash messages.
     */
    public function getFlash():array
    {
        // Recovery of flash messages.
        $flash['flash'] = $this->flashMessage;
        
        // Destroying variables
        unset($_SESSION['flash'], $this->flashMessage);
        
        // Returning flash messages
        return $flash;
    }

    /**
     * Method adding flash messages.
     * @var string $type    The type of flash message.['success', 'info', 'warning', 'danger']
     * @var string $flash   The content of the flash message.
     */
    public function addFlash(string $type, string $flash)
    {
        // If the type is not defined, an exception is raised.
        if (!in_array($type, $this->flashType)) {
            throw new \InvalidArgumentException('Le type de message flash est érroné.');
        }
        // Adding the message flash
        $this->flashMessage[$type][] = $flash;
        
        //Create a session variable, to pass the message from page to page.
        $_SESSION['flash'] = $this->flashMessage;
    }
}
