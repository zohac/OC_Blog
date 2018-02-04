<?php
namespace app\model;

use \ZCFram\PDOManager;
use \ZCFram\User;

/**
 * The login Manager
 */
class LoginManager extends PDOManager
{

    /**
     * Verify that the user is registered, and retrieve this is information
     * @param  string $email
     * @param  string $password
     * @return array            The user information
     */
    public function getUser(string $email, string $password)
    {
        // SQL request
        $sql = "
        SELECT
            *,
            id AS userId
        FROM blog.user
        WHERE user.email = :email
            AND user.password = :password
            AND user.status != 'banned'
        ";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates values with parameters
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        $requete->bindValue(':password', $password, \PDO::PARAM_STR);

        // Execute the sql query
        $requete->execute();

        // Retrieves information
        $userInfo = $requete->fetch();

        if ($userInfo === false) {
            return false;
        }
        // Returns the information
        return new User($userInfo);
    }
}
