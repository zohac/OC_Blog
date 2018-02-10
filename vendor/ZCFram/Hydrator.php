<?php
namespace ZCFram;

/**
 * Hydrate a classe
 */
trait Hydrator
{
    /**
     * Hydrate a classe
     *
     * @param array|object $data
     * @return void
     */
    public function hydrate($data)
    {
        foreach ($data as $key => $value) {
            // Formatting the method name
            $method = 'set'.ucfirst($key);

            // If the method exists, we call it
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }
}
