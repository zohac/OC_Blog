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
     * Returns a connection object to the database
     * @return object PDOManager
     */
    public static function getConnexionDB()
    {
        $config = self::getConfigurator('database');
        return new PDOManager($config);
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
     * Return an instance of Swift_Mailer
     * @return object Swift_Mailer
     */
    public static function getMailer()
    {
        // Get configuration
        $mail = self::getConfigurator('mail');
        // Create the Transport
        $transport = (new \Swift_SmtpTransport($mail['host'], 25));

        // Create the Mailer using your created Transport
        return new \Swift_Mailer($transport);
    }

    /**
     * Return an instance of Swift_Message
     * @return object Swift_Message
     */
    public static function getSwiftMessage()
    {
        $privatekey = \file_get_contents(__DIR__.'/../../../dkim.private.key');
        $signer = new \Swift_Signers_DKIMSigner($privatekey, 'jouan.ovh', 'default');

        $message =  new \Swift_Message();
        $message->attachSigner($signer);
        $message
            ->setSubject('Demande de contact')
            ->setFrom(['contact@jouan.ovh' => 'jouan.ovh'])
            ->setTo(['fenrir0680@gmail.com' => 'jouan.ovh']);
        return $message;
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
}
