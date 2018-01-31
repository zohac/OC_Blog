<?php
namespace ZCFram;

/**
 * Retrieves the desired configuration, in the config.xml file
 */
class Configurator
{

    /**
     * The path to the config file
     */
    const CONFIG_PATH = '/../../app/config/config.xml';

    /**
     * Retrieves the desired configuration, in the config.xml file
     * @var string $config  The name of the configuration to be found
     * @return array        Set of the configuration returned in table form
     */
    public function getConfig(string $config):array
    {
        // Retrieves the path of the config.xml file
        $configPath = realpath(__DIR__.self::CONFIG_PATH);

        // Load the file and recover all child nodes
        $xml = new \DOMDocument;
        $xml->load($configPath);
        $variable = $xml->getElementsByTagName($config);
        $item = $variable->item(0);
        $childNodes = $item->childNodes;

        //Declaration of a variable array, to store the requested configuration
        $var = [];

        // For each child node, the value is recovered
        foreach ($childNodes as $value) {
            $var = \array_merge($var, [$value->nodeName => $value->nodeValue]);
        }
        // Return the requested configuration
        return $var;
    }
}
