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
        // Send the header
        $this->addHeader('HTTP/1.1 '.$this->status.' '.$this->statusCode[$this->status]);
        //And the view
        echo($view);
        exit;
    }

    /**
     * Change compared to the setcookie () function: the last argument is set to true by default.
     * @param  string $name     The name of the cookie
     * @param  string $value    The value of the cookie
     * @param  int $expire      The lifetime of the cookie in s
     * @param  string $path     The path on the server in which the cookie will be available on
     * @param  string $domain   The (sub)domain that the cookie is available to
     * @param  bool $value      Indicates that the cookie should only be transmitted over a secure HTTPS
     *                          connection from the client
     * @param  bool $value      When TRUE the cookie will be made accessible only through the HTTP protocol.
     */
    public function setCookie(
        string $name,
        string $value = '',
        int $expire = 0,
        string $path = null,
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = true
    ) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}
