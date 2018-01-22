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

    public function getFlash():array
    {
        $flash['flash'] = $this->flashMessage;
        unset($this->flashMessage);
        unset($_SESSION['flash']);
        return $flash;
    }

    public function addFlash(string $type, string $flash)
    {
        if (!in_array($type, $this->flashType)) {
            throw new \InvalidArgumentException('Les type de message flash est érroné.');
        }
        $this->flashMessage[$type][] = $flash;
        $_SESSION['flash'] = $this->flashMessage;
    }
}
