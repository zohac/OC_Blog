<?php
namespace ZCFram;

/**
 * DIC : Dependence Injection Container
 */
abstract class Container
{

    /**
     * The path to the config file
     */
    const CONFIG_PATH = '/../../app/config/config.xml';

    private static $HTTPResponse = null;

    /**
     * Returns a connection object to the database
     * @return object PDOManager
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
     * @param string $config The name of the tag to find
     * @return array $var
     */
    public static function getConfig(string $config)
    {
        $configPath = realpath(__DIR__.self::CONFIG_PATH);

        $xml = new \DOMDocument;
        $xml->load($configPath);
        $variable = $xml->getElementsByTagName($config);

        switch ($config) {
            case 'bdd':
                foreach ($variable as $value) {
                    $var = [
                        'host' => $value->getAttribute('host'),
                        'dbname' => $value->getAttribute('dbname'),
                        'user' => $value->getAttribute('user'),
                        'password' => $value->getAttribute('password')
                    ];
                }
                break;
            case 'mail':
                break;
        }


        return $var;
    }

    /**
     * Returns a connection object to the database
     * @return object PDOManager
     */
    public static function getConnexionDB()
    {
        return new PDOManager(self::getConfig('bdd'));
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
        // Create the Transport
        $transport = (new \Swift_SmtpTransport('localhost', 25))
         // ->setUsername('your username')
         // ->setPassword('your password')
        ;

        // Create the Mailer using your created Transport
        return new \Swift_Mailer($transport);
    }

    /**
     * Return an instance of Swift_Message
     * @return object Swift_Message
     */
    public static function getSwiftMessage()
    {
        $message =  new \Swift_Message();
        $message->setSubject('Demande de contact')
            ->setFrom(['contact@jouan.ovh' => 'jouan.ovh'])
            ->setTo(['fenrir0680@gmail.com' => 'jouan.ovh']);
        return $message;
    }
}
