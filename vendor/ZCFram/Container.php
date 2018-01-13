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

        foreach ($variable as $value) {
            $var = [
                'host' => $value->getAttribute('host'),
                'dbname' => $value->getAttribute('dbname'),
                'user' => $value->getAttribute('user'),
                'password' => $value->getAttribute('password')
            ];
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
}
