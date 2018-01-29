<?php
namespace ZCFram;

/**
 * Class representing the client request.
 */
class HTTPRequest
{

    /**
     * Method retrieving the server name.
     * @return string $_SERVER['SERVER_NAME']
     */
    public function serverName():string
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Method retrieving the request uri.
     * @return string $_SERVER['REQUEST_URI']
     */
    public function requestURI():string
    {
        return $_SERVER['REQUEST_URI'];
    }
}
