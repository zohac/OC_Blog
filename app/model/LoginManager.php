<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class LoginManager extends Manager
{

    public function getUser(string $email, string $password)
    {
        $sql = "SELECT * FROM blog.user WHERE user.email = :email AND user.password = :password";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
		$requete->bindValue(':password', $password, \PDO::PARAM_STR);
        $requete->execute();
        $userInfo = $requete->fetch();
        
        return $userInfo;
    }
}
