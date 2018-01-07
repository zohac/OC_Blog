<?php
namespace app;

use \ZCFram\Controller;
use \ZCFram\ViewController;

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
     * Execute the index page
     */
    public function executeError()
    {
        $statut = \explode('.', $_GET['rt']);

        if (\array_key_exists((int) $statut[0], $this->statusCode)) {
            $this->setParams([
                'error' => $statut[0],
                'message' =>  $this->statusCode[$statut[0]]
            ]);
        }

        $this->setView(\strtolower($this->action).'.twig');
        $this->getView();
    }
}
