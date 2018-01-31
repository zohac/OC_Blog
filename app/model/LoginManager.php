<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class LoginManager extends PDOManager
{

    public function getUser(string $email, string $password)
    {
        // SQL request
        $sql = "
        SELECT *
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

        // Returns the information
        return $userInfo;
    }
}
