<?php
namespace app\model;

use \ZCFram\Manager;

/**
 * Class allowing the call to the DB concerning the Post, using PDO
 */
class PostManager extends Manager
{

    /**
     * The list of all publish Post
     * @return array The list of all publish Post
     */
    public function getList()
    {
        // SQL request
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

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Retrieves information from DB
        $listPosts = $this->DB
            ->query($sql)
            ->fetchAll();

        // Returns the information in array
        return $listPosts;
    }

    /**
     * A publish Post
     * @param  int    $id The id of a post
     * @return array The publish Post
     */
    public function getPost(int $id)
    {
        // SQL request
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

        // Transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with the id parameter
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query and return a bool
        $requete->execute();

        // Retrieves information from a post
        $PostInfo = $requete->fetch();

        // Returns the information of a post in array
        return $PostInfo;
    }

    /**
     * Check if the post has comment
     * @param  int    $id The id of a post
     * @return bool
     */
    public function postHasComment(int $id):bool
    {
        // SQL request
        $sql = "
        SELECT id
        FROM blog.comment
        WHERE comment.status = 'approve' AND comment.post_id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with the id parameter
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query and return a bool
        $requete->execute();

        $result = $requete->fetchColumn();

        if ($result === false) {
            return false;
        }
        return true;
    }
}
