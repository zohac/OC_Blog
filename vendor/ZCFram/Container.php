<?php
namespace ZCFram;

/**
 * DIC : Dependence Injection Container
 */
abstract class Container
{

    /**
     * Variable representing the HTTP response as a singleton
     * @var objet|null
     */
    private static $HTTPResponse = null;

    /**
     * Returns a HTTPResponse object
     * @return object HTTPResponse
     */
    public static function getHTTPResponse()
    {
        if (self::$HTTPResponse === null) {
            self::$HTTPResponse = new HTTPResponse();
        }
        return self::$HTTPResponse;
    }

    /**
     * Returns the parameters in the config file
     * @param string $value The name of the config to find
     * @return object
     */
    public static function getConfigurator(string $value)
    {
        $config = new Configurator;
        return $config->getConfig($value);
    }

    /**
     * Returns an instance of the Form Controller
     * @return object Validator
     */
    public static function getValidator()
    {
        return new Validator();
    }

    /**
     * Returns an instance of the Email Controller
     * @return object Email
     */
    public static function getEmail()
    {
        return new Email();
    }

    /**
     * Returns an instance of the Encryption Controller
     * @return object Encryption
     */
    public static function getEncryption()
    {
        return new Encryption();
    }

    /**
     * Returns an instance of the Token Controller
     * @return object Token
     */
    public static function getToken()
    {
        return new Token;
    }

    /**
     * Returns an instance of the User Controller
     * @return object User
     */
    public static function getUser()
    {
        return new User;
    }
}
