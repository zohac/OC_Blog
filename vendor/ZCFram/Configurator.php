<?php
namespace ZCFram;

/**
 *
 */
class Configurator
{

    /**
     * The path to the config file
     */
    const CONFIG_PATH = '/../../app/config/config.xml';

    protected $config;

    public function __construct(string $config)
    {
        $this->setConfig($config);
    }

    protected function setConfig(string $config)
    {
        $this->config = $config;
    }

    public function getConfig():array
    {
        $configPath = realpath(__DIR__.self::CONFIG_PATH);

        $xml = new \DOMDocument;
        $xml->load($configPath);
        $variable = $xml->getElementsByTagName($this->config);
        $variable = $variable->item(0);
        $variable = $variable->childNodes;

        $var = [];
        foreach ($variable as $value) {
            $var = \array_merge($var, [$value->nodeName => $value->nodeValue]);
        }
        return $var;
    }
}
