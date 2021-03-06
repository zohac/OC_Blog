<?php
namespace ZCFram;

use \PDO;

/**
 * Class handling PDO connections.
 */
class PDOManager
{
    /**
     * A PDO instance
     * @var PDO
     */
    protected $DB;

    /**
     * The host name for the connexion to the database
     * @var string
     */
    private $host;

    /**
     * The name of the database
     * @var string
     */
    private $dbname;

    /**
     * The name of the user for the connexion to the database
     * @var string
     */
    private $user;

    /**
     * The password for the connexion to the database
     * @var string
     */
    private $password;

    /**
     * Retrieving DB connection configuration, and connection
     * @param array $config
     */
    public function __construct(array $config)
    {
        // Recording DB connection data
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->user = $config['user'];
        $this->password = $config['password'];

        // DB connection request
        $this->getConnexion();
    }

    /**
     * Returns a connection object to the DB by initiating the connection as needed
     * @return PDO
     */
    private function getConnexion()
    {
        // If the variable is strictly null
        if ($this->DB === null) {
            // Create a new connection to DB using PDO
            $this->DB = new PDO(
                'mysql:host='.$this->host.';dbname='.$this->dbname.';charset=utf8',
                $this->user,
                $this->password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        }
    }
}
