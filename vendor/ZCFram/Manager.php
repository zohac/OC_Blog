<?php
namespace ZCFram;

/**
 *
 */
class Manager
{
    /**
     * An instance of PDO
     * @var object PDO
     */
    protected $DB;

    public function __construct(PDOManager $DB)
    {
        $this->DB = $DB->getDB();
    }
}
