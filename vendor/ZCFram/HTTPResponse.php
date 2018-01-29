<?php
namespace ZCFram;

use Psr\Http\Message\ResponseInterface;

/**
 * class that attempts to return the response to the browser
 * or perform a redirection.
 */
class HTTPResponse
{

    /**
     * Map of standard HTTP status code found at
     * https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
     * @var array Map of standard HTTP status code
     */
    private $statusCode = [
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Found',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out'
    ];

    /**
     * the status code to send to the browser
     * @var int
     */
    private $status = 200;

    /**
     * send a header
     * @param string $header the header to send
     */
    public function addHeader($header)
    {
        header($header);
    }

    /**
     * @param int $statuts the status code
     */
    public function setStatus(int $statuts)
    {
        // Check that the status code is an authorized code.
        if (array_key_exists($statuts, $this->statusCode)) {
            $this->status = $statuts;
        }
    }

    /**
     * Launches a redirection
     * @param  string $uri The uri for the redirection
     */
    public function redirection(string $uri)
    {
        //Create HTTP header and redirect.
        $this->addHeader('HTTP/1.1 '.$this->status.' '.$this->statusCode[$this->status]);
        $this->addHeader('Location: '.$uri);
        exit;
    }

    /**
     * Send a view to the browser
     * @param  string $view The view to send
     * @return [type]       [description]
     */
    public function send(string $view)
    {
        $this->addHeader('HTTP/1.1 '.$this->status.' '.$this->statusCode[$this->status]);
        echo($view);
        exit;
    }

 /*
    // TODO : suprimmer
    //TODO
    // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
    public function setCookie(
        $name,
        $value = '',
        $expire = 0,
        $path = null,
        $domain = null,
        $secure = false,
        $httpOnly = true
    ) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    } */
}
