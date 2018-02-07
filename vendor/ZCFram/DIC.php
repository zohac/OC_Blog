<?php
namespace ZCFram;

/**
 *
 */
class DIC
{
    /**
     * Register containing all classes of the container
     * @var array
     */
    private $registry = [];

    /**
     *  Register containing all instance of classes
     * @var array
     */
    private $instance = [];

    public function __construct(array $params)
    {
        foreach ($params as $key => $resolver) {
            $this->set($key, $resolver);
        }
    }

    public function set($key, callable $resolver)
    {
        $this->registry[$key] = $resolver;
    }

    public function get($key)
    {
        if (!isset($this->instance[$key])) {
            $this->instance[$key] = $this->registry[$key]();
        }
        return $this->instance[$key];
    }
}
