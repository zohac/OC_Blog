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
    public function getListOfPost(string $status = null):array
    {
        // Add a WHERE clause
        if ($status === null) {
            $status =  " WHERE post.status != 'Trash' ORDER BY id DESC";
        } else {
            $status =  " WHERE post.status = '$status' ORDER BY id DESC";
        }

        // SQL request
        $sql = "
        SELECT
            id AS postID,
            id AS imgPath,
            title,
            SUBSTRING(post FROM 1 FOR 120) AS chapo,
            DATE_FORMAT(modificationDate, '%Y-%M-%d') AS modificationDate
        FROM blog.post".$status;

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
            DATE_FORMAT(post.creationDate, '%Y-%m-%d') AS creationDate,
            DATE_FORMAT(post.modificationDate, '%Y-%m-%d') AS modificationDate,
            DATE_FORMAT(post.modificationDate, '<span>%d</span> %M %Y') AS datePost,
            post.status,
            user.pseudo AS author
        FROM blog.post
        INNER JOIN user
            ON user.id = post.author_id
        WHERE post.id = :id";

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

    /**
     * A publish Post
     * @param  int    $id The id of a post
     * @return object The publish Post
     */
    public function getPublishPost(int $id)
    {
        // SQL request
        $sql = "
        SELECT
            post.id AS postID,
            post.id AS imgPath,
            title,
            SUBSTRING(post FROM 1 FOR 120) AS chapo,
            post,
            DATE_FORMAT(post.creationDate, '%Y-%m-%d') AS creationDate,
            DATE_FORMAT(post.modificationDate, '%Y-%m-%d') AS modificationDate,
            DATE_FORMAT(post.modificationDate, '<span>%d</span> %M %Y') AS datePost,
            post.status,
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

    /**
     * Updating a post
     * @param post $post
     * @return bool
     */
    public function updatePost(post $post):bool
    {
        // SQL request
        $sql = "
        UPDATE post
        SET post.title = :title, post.post = :post, post.status = :status, post.modificationDate = NOW()
        WHERE id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':title', $post->getTitle(), \PDO::PARAM_STR);
        $requete->bindValue(':post', $post->getPost(), \PDO::PARAM_STR);
        $requete->bindValue(':status', $post->getStatus(), \PDO::PARAM_STR);
        $requete->bindValue(':id', $post->getPostID(), \PDO::PARAM_INT);

        // Execute the sql query and return bool
        return $requete->execute();
    }

    /**
     * Insert a new post
     * @param post $post
     * @return bool|int the last insert id
     */
    public function insertPost(post $post)
    {
        // SQL request
        $sql = "
        INSERT INTO `blog`.`post`
            (author_id, title, post, creationDate, modificationDate, status)
        VALUES
            (:author_id, :title, :post, NOW(), NOW(), :status)";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':author_id', $post->getAuthor(), \PDO::PARAM_INT);
        $requete->bindValue(':title', $post->getTitle(), \PDO::PARAM_STR);
        $requete->bindValue(':post', $post->getPost(), \PDO::PARAM_STR);
        $requete->bindValue(':status', $post->getStatus(), \PDO::PARAM_STR);

        // Execute the sql query and return bool
        $result = $requete->execute();
        if ($result) {
            return (int)$this->DB->lastInsertId();
        }
        return false;
    }

    /**
     * Delete a post
     * @param  int  $id the id post
     * @return bool
     */
    public function deletePost(int $id):bool
    {
        // SQL request
        $sql = "
        UPDATE post
        SET status = 'Trash', modificationDate = NOW()
        WHERE id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query and return bool
        return $requete->execute();
    }
}
