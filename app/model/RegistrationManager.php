<?php
namespace app\model;

use \ZCFram\PDOManager;

/**
 * The registration manager
 */
class RegistrationManager extends PDOManager
{

    /**
     * Verifies that the user exists
     * @param  string $email
     * @return bool
     */
    public function userExist(string $email):bool
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

    /**
     * Verifies that the user is banned
     * @param  string $email
     * @return bool
     */
    public function userBanned(string $email):bool
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

    /**
     * Register a new user
     * @param  string $pseudo
     * @param  string $email
     * @param  string $password
     * @return bool
     */
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

    /**
     * Make sure this is the first recording
     * @return bool
     */
    public function firstRegistration():bool
    {
        // SQL request
        $sql = " SELECT COUNT(*) AS user FROM user";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Execute the sql query
        $requete->execute();

        // Retrieves information
        $reponse = $requete->fetch();

        // If there is a record, we return true
        if ((int)$reponse['user'] === 0) {
            return true;
        }
        // else
        return false;
    }

    /**
     * Register the user as administrator
     * @param  string $pseudo
     * @param  string $email
     * @param  string $password
     * @return bool
     */
    public function registrationAdministrator(string $pseudo, string $email, string $password):bool
    {
        // SQL request
        $sql = "
		INSERT INTO `blog`.`user`
			(pseudo, email, password, role, status)
		VALUES (
			:pseudo,
			:email,
			:password,
			'Administrator',
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
