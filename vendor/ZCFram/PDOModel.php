<?php
namespace ZCFram;

/**
 * Abstract class handling PDO connections.
 */
abstract class PDOModel
{

    /**
     * A PDO instance
     * @var PDO
     */
    protected $bdd;

    /**
     * Returns a connection object to the DB by initiating the connection as needed
     * @return PDO
     */
    private function getConnexion()
    {
        if ($this->bdd == null) {
            // Création de la connexion
            $this->bdd = new PDO(
                'mysql:host=localhost;dbname=blog;charset=utf8',
                'root',
                'root',
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        }
        return $this->bdd;
    }
}
