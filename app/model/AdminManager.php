<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class AdminManager extends Manager
{

    public function getList()
    {
        $sql = "
        SELECT
            id,
            title,
			DATE_FORMAT(modificationDate, '%e %M %Y') AS date
        FROM blog.post
        ORDER BY id DESC";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");
        $listPosts = $this->DB
            ->query($sql)
            ->fetchAll();

        return $listPosts;
    }

    public function getNumberOfUsers()
    {
        $sql = "SELECT COUNT(*) AS numberOfUsers FROM blog.user";

        $numberOfUsers = $this->DB
            ->query($sql)
            ->fetch();

        return $numberOfUsers;
    }

    public function getNumberOfPosts()
    {
        $sql = "SELECT COUNT(*) AS numberOfPosts FROM blog.post";

        $numberOfPosts = $this->DB
            ->query($sql)
            ->fetch();

        return $numberOfPosts;
    }

    public function getPost(int $id)
    {
        $sql = "
        SELECT
            title,
            post,
            DATE_FORMAT(modificationDate, '%e %M %Y') AS date,
            user.pseudo AS author
        FROM blog.post
        INNER JOIN user
            ON user.id = post.author_id
        WHERE post.id = :id";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");
        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        $requete->execute();
        $PostInfo = $requete->fetch();

        return $PostInfo;
    }
}
