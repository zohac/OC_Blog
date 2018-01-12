<?php
namespace app;

use \ZCFram\Controller;

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
    public $errorCode;

    public function __construct(\Exception $e)
    {
        $this->e = $e;
        $action = 'error';
        $module = 'error';

        parent::__construct($action, $module);
    }

    /**
     * Execute the index page
     */
    public function executeError()
    {
        $this->getStatusCode();

        if (\array_key_exists($this->errorCode, $this->statusCode)) {
            $this->setParams([
                'error' => $this->errorCode,
                'status' => $this->statusCode[$this->errorCode],
                'message' => $this->e->getMessage()
            ]);
        }
    }

    private function getStatusCode()
    {
        switch (get_class($this->e)) {
            case 'RuntimeException':
                $this->errorCode = 404;
                break;
            case 'BadFunctionCallException' or 'InvalidArgumentException':
                $this->errorCode = 500;
                break;
        }
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
