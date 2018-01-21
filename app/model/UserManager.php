<?php
namespace app\model;

use \ZCFram\Manager;

/**
 * Class allowing the call to the DB concerning the user, using PDO
 */
class UserManager extends Manager
{

    /**
     * The list of all users except those banned
     * @return array The list of all users except those banned
     */
    public function getListUser():array
    {
        // SQL request
        $sql = "
        SELECT id, pseudo, email, role
        FROM blog.user
        WHERE status != 'banned'";

        // Return an array of users
        return $this->DB
            ->query($sql)
            ->fetchAll();
    }

    /**
     * Marks a banned user as a delete mark
     * @param  int    $id The id of an user
     * @return bool
     */
    public function deleteUser(int $id):bool
    {
        // SQL request
        $sql = "
        UPDATE user
        SET status = 'banned'
        WHERE id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with the id parameter
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query and return a bool
        return $requete->execute();
    }
}
