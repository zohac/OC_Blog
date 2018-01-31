<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class RegistrationManager extends PDOManager
{

    public function userExist(string $email)
    {
        // SQL request
        $sql = " SELECT COUNT(*) AS user FROM user WHERE email = :email";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);
        
        // Associates a value with the email parameter
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        
        // Execute the sql query
        $requete->execute();
        
        // Retrieves information
        $reponse = $requete->fetch();

        // If there is a record, we return true
        if ($reponse['user'] == 1) {
            return true;
        }
        // else
        return false;
    }

    public function userBanned(string $email)
    {
        // SQL request
        $sql = " SELECT COUNT(*) AS banned FROM user WHERE email = :email AND status = 'banned'";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);
        
        // Associates a value with the email parameter
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        
        // Execute the sql query
        $requete->execute();
        
        // Retrieves information
        $reponse = $requete->fetch();

        // If there is a record, we return true
        if ($reponse['banned'] == 1) {
            return true;
        }
        // else
        return false;
    }

    public function registration(string $pseudo, string $email, string $password):bool
    {
        // SQL request
        $sql = "
		INSERT INTO `blog`.`user`
			(pseudo, email, password, role, status)
		VALUES (
			:pseudo,
			:email,
			:password,
			'Subscriber',
			'authorized'
		)";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);
        
        // Associates values with parameters
        $requete->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        $requete->bindValue(':password', $password, \PDO::PARAM_STR);
        
        // Execute the sql query return a bool
        return $requete->execute();
    }
}
