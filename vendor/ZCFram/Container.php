<?php
namespace ZCFram;

/**
 * DIC : Dependence Injection Container
 */
abstract class Container
{

    const CONFIG_PATH = '/../../app/config/config.xml';

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

    public static function getConnexionDB()
    {
        return new PDOManager(self::getConfig('bdd'));
    }
}
