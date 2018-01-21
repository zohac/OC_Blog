<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class RegistrationManager extends Manager
{

    public function userExist(string $email)
    {
        $sql = " SELECT COUNT(*) AS user FROM user WHERE email = :email";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        $requete->execute();
        $reponse = $requete->fetch();

        if ($reponse['user'] == 1) {
            return true;
        }
        return false;
    }

    public function userBanned(string $email)
    {
        $sql = " SELECT COUNT(*) AS banned FROM user WHERE email = :email AND status = 'banned'";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        $requete->execute();
        $reponse = $requete->fetch();

        if ($reponse['banned'] == 1) {
            return true;
        }
        return false;
    }

    public function registration(string $pseudo, string $email, string $password)
    {
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

            $requete = $this->DB->prepare($sql);
            $requete->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
            $requete->bindValue(':email', $email, \PDO::PARAM_STR);
            $requete->bindValue(':password', $password, \PDO::PARAM_STR);
            return $requete->execute();
    }
}
