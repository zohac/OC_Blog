<?php
namespace ZCFram;

/**
 * Retrieves the desired configuration, in the config.xml file
 */
class Configurator
{
    /**
     * The config of the app
     * @var array
     */
    protected $config = [];

    /**
     * @param string $congifPath
     */
    public function __construct(string $congifPath)
    {
        // Load the file and recover all child nodes
        $xml = new \DOMDocument;
        $xml->load($congifPath);
        $item = $xml->getElementsByTagName('config')->item(0);
        $childNodes = $item->childNodes;

        // For each child node, the value is recovered
        foreach ($childNodes as $value) {
            //$this->config = \array_merge($this->config, [$value->nodeName => $value->nodeValue]);
            if ($value->nodeName != '#text') {
                // Load the childNode
                $item = $xml->getElementsByTagName($value->nodeName)->item(0);
                $child = $item->childNodes;

                // table containing the node name and its value
                $var=[];

                // For each child node, the value is recovered
                foreach ($child as $val) {
                    if ($val->nodeName != '#text') {
                        // Adding the node to the variable array
                        $var = \array_merge($var, [$val->nodeName => $val->nodeValue]);
                    }
                }
                // Adding the children's board to the parent key
                $this->config[$value->nodeName] = $var;
            }
        }
    }

    /**
     * Retrieves the desired configuration, in the config.xml file
     * @var string $config  The name of the configuration to be found
     * @return array        Set of the configuration returned in table form
     */
    public function getConfig(string $config): array
    {
        return $this->config[$config];
    }
}
