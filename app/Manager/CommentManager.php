<?php
namespace app\Manager;

use \ZCFram\PDOManager;
use \app\Entity\Comment;

/**
 * Class allowing the call to the DB concerning the Post, using PDO
 */
class CommentManager extends PDOManager
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
            comment,
            DATE_FORMAT(comment.creationDate, '%e-%M %Y') AS creationDate,
            user.pseudo AS author
        FROM blog.comment
		INNER JOIN user
            ON user.id = comment.author_id
        WHERE comment.status = 'approve' AND comment.post_id = :id";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with the id parameter
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query
        $requete->execute();

        // Retrieves information from DB
        $listOfComment = $requete->fetchALL();

        foreach ($listOfComment as $key => $comment) {
            $listOfComment[$key] = new Comment($comment);
        }

        // Returns the information in array
        return $listOfComment;
    }

    /**
     * The list of all publish Post
     * @param  int    $id The id of a post
     * @return array The list of all publish Post
     */
    public function insertComment(Comment $comment):bool
    {
        // SQL request
        $sql = "
        INSERT INTO `blog`.`comment`
            (post_id, author_id, comment, creationDate, status)
        VALUES
            (:post_id, :author_id, :comment, NOW(), 'hold')";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates values with parameters
        $requete->bindValue(':post_id', $comment->getIdPost(), \PDO::PARAM_INT);
        $requete->bindValue(':author_id', $comment->getAuthor(), \PDO::PARAM_INT);
        $requete->bindValue(':comment', $comment->getComment(), \PDO::PARAM_STR);

        // Execute the sql query and return a booleen
        return $requete->execute();
    }
}
