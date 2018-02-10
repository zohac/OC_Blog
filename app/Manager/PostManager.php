<?php
namespace app\Manager;

use \ZCFram\PDOManager;
use \app\Entity\Post;

/**
 * Class allowing the call to the DB concerning the Post, using PDO
 */
class PostManager extends PDOManager
{
    /**
     * The list of all publish Post
     * @return array The list of all publish Post
     */
    public function getList():array
    {
        // SQL request
        $sql = "
        SELECT
            id AS postID,
            id AS imgPath,
            title,
            SUBSTRING(post FROM 1 FOR 120) AS chapo,
            DATE_FORMAT(modificationDate, '%e-%M %Y') AS modificationDate
        FROM blog.post
        WHERE post.status = 'Publish'
        ORDER BY id DESC";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Retrieves information from DB
        $listOfPosts = $this->DB
            ->query($sql)
            ->fetchAll();

        foreach ($listOfPosts as $key => $comment) {
            $listOfPosts[$key] = new Post($comment);
        }
        // Returns the information in array
        return $listOfPosts;
    }

    /**
     * A publish Post
     * @param  int    $id The id of a post
     * @return object The publish Post
     */
    public function getPost(int $id)
    {
        // SQL request
        $sql = "
        SELECT
            post.id AS postID,
            post.id AS imgPath,
            title,
            SUBSTRING(post FROM 1 FOR 120) AS chapo,
            post,
            DATE_FORMAT(modificationDate, '%e-%M %Y') AS modificationDate,
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

        // Execute the sql query
        $requete->execute();

        // Retrieves information from a post
        $PostInfo = $requete->fetch();

        // Create new post object
        $post = new Post($PostInfo);

        // Return the information of an object post
        return $post;
    }
}
