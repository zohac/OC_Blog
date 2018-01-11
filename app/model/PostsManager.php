<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class PostsManager extends Manager
{

    public function getList(int $debut = -1, int $limite = -1)
    {
        $sql = "
        SELECT
            id,
            title,
            SUBSTRING(post FROM 1 FOR 160) AS chapo,
            DATE_FORMAT(modificationDate, '%e') AS day,
            DATE_FORMAT(modificationDate, '%M %Y') AS monthYear
        FROM blog.post
        WHERE post.status = 'Publish'
        ORDER BY id DESC";

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");
        $listPosts = $this->DB
            ->query($sql)
            ->fetchAll();

        return $listPosts;
    }

    public function getPost(int $id)
    {
        $sql = "
        SELECT
            title,
            SUBSTRING(post FROM 1 FOR 160) AS chapo,
            post,
            DATE_FORMAT(modificationDate, '%e') AS day,
            DATE_FORMAT(modificationDate, '%M %Y') AS monthYear,
            user.pseudo AS author
        FROM blog.post
        INNER JOIN user
            ON user.id = post.author_id
        WHERE post.status = 'Publish' AND post.id = :id";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");
        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        $requete->execute();
        $PostInfo = $requete->fetch();

        return $PostInfo;
    }
}
