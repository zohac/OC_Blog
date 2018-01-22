<?php
namespace app\model;

use \ZCFram\Manager;

/**
 * Class allowing the call to the DB concerning the Post, using PDO
 */
class CommentManager extends Manager
{

    /**
     * The list of all publish Post
     * @param  int    $id The id of a post
     * @return array The list of all publish Post
     */
    public function getComment($id)
    {
        // SQL request
        $sql = "
        SELECT
			comment.id,
			comment.title,
			DATE_FORMAT(comment.creationDate, '%e') AS day,
            DATE_FORMAT(comment.creationDate, '%M %Y') AS monthYear,
            user.pseudo AS author
        FROM blog.comment
		INNER JOIN user
            ON user.id = comment.author_id
        WHERE comment.status = 'approve' AND comment.post_id = :id";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Retrieves information from DB
        $listPosts = $requete->fetchAll();

        // Returns the information in array
        return $listPosts;
    }

    /**
     * The list of all publish Post
     * @param  int    $id The id of a post
     * @return array The list of all publish Post
     */
    public function insertComment(array $comment)
    {
        $sql = "
        INSERT INTO `blog`.`comment`
            (post_id, author_id, comment, creationDate, status)
        VALUES
            (:post_id, :author_id, :comment, NOW(), 'hold')";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':post_id', $comment['post_id'], \PDO::PARAM_INT);
        $requete->bindValue(':author_id', $comment['author_id'], \PDO::PARAM_INT);
        $requete->bindValue(':comment', $comment['comment'], \PDO::PARAM_STR);
        return $requete->execute();
    }
}
