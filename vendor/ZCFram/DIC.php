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

    /**
     * Loads a parameter table to initialize the container.
     * @param array $params
     */
    public function __construct(array $params)
    {
        foreach ($params as $key => $resolver) {
            $this->set($key, $resolver);
        }
    }

    /**
     * Set an entry of the container by its identifier and and how to solve it.
     *
     * @param string $key
     * @param callable $resolver
     * @return void
     */
    public function set(string $key, callable $resolver)
    {
        $this->registry[$key] = $resolver;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $key
     * @return mixed Entry.
     */
    public function get(string $key)
    {
        // Returns a class instance in singleton form.
        // If an instance exists, it is returned,
        // otherwise it is loaded in the instance table.
        if (!isset($this->instance[$key])) {
            $this->instance[$key] = $this->registry[$key]();
        }
        // Return an instance of the requested class
        return $this->instance[$key];
    }
}
