<?php
namespace ZCFram;

/**
 * Controller who manages the index and blog posts
 */
class ErrorController extends Controller
{

    /**
     * Map of standard HTTP error status code found at
     * https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
     * @var array Map of standard HTTP status code
     */
    private $statusCode = [
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out'
    ];

    /**
     * An instance of Exception class
     * @var Exception
     */
    private $e;

    /**
     * The status code
     * @var int
     */
    protected $errorCode;

    /**
     * An instance of the DIC
     * @var object DIC
     */
    protected $container;

    /**
     * @var Exception $e
     */
    public function __construct(\ZCFram\DIC $container, \Exception $e)
    {
        parent::__construct($container->get('Router'));

        // We recover the container
        $this->container = $container;
        // We recover the exception
        $this->e = $e;
        // We define the view to call
        $this->setView('error');
        // We define the action to execute
        $this->setAction('error');
    }

    /**
     * Execute the error page
     * @return 'The view'
     */
    public function executeError()
    {
        // The error code returned by the exception type is retrieved.
        $this->getStatusCode();

        // If the error code is set correctly
        if (\array_key_exists($this->errorCode, $this->statusCode)) {
            // We define the parameters to send to the view
            $this->setParams([
                'error' => $this->errorCode,
                'status' => $this->statusCode[$this->errorCode],
                'message' => $this->e->getMessage()
            ]);
        }

        // We define the status code to return to the user's browser.
        $response = $this->container->get('HTTPResponse');
        $response->setStatus($this->errorCode);

        // View recovery and display
        $this->getView();
        $this->send();
    }

    /**
     * Define the status code
     * @return int The status code
     */
    private function getStatusCode()
    {
        //The error code is defined according to the type of exception.
        switch (get_class($this->e)) {
            case 'RuntimeException':
                $this->errorCode = 404;
                break;

            default:
                $this->errorCode = 500;
                break;
        }
    }
}
