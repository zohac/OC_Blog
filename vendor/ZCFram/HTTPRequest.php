<?php
namespace ZCFram;

/**
 *
 */
class HTTPRequest
{

    public function serverName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function requestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
