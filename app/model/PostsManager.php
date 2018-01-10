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
}
