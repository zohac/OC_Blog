<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class RegistrationManager extends Manager
{

    public function registration(string $pseudo, string $email, string $password)
    {
        $sql = "
		INSERT INTO `blog`.`user`
			(pseudo, email, password, role)
		VALUES (
			:pseudo,
			:email,
			:password,
			'Subscriber'
		)";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        $requete->bindValue(':password', $password, \PDO::PARAM_STR);
        return $requete->execute();
    }
}
