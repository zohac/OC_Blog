<?php
namespace app\Manager;

use \ZCFram\PDOManager;
use \ZCFram\Guest;
use \app\Entity\Comment;
use \app\Entity\Post;
use \app\Entity\User;

/**
 * Class allowing the call to the DB concerning the Post, using PDO
 */
class CommentManager extends PDOManager
{

    /**
     * List of comments associated with a post
     *
     * @param Post      $post
     * @return array    An array of comment class
     */
    public function getComment(Post $post):array
    {
        // SQL request
        $sql = "
        SELECT
			comment.id AS commentID,
            comment,
            DATE_FORMAT(comment.creationDate, '%e %M %Y') AS creationDate,
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
        $requete->bindValue(':id', $post->getPostID(), \PDO::PARAM_INT);

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

    /**
     * Retrieves the list of user comments
     *
     * @param User     $user the user
     * @return array    the list of user comments
     */
    public function getUserComments(User $user): array
    {
        // SQL request
        $sql = "
        SELECT
            comment.id AS commentID,
			comment,
            DATE_FORMAT(comment.creationDate, '%e/%m/%Y') AS creationDate,
            comment.status,
            user.pseudo AS author,
			post.title AS blogTitle,
			post.id AS idPost
        FROM blog.comment
        INNER JOIN user
            ON user.id = comment.author_id
		INNER JOIN post
            ON post.id = comment.post_id
		WHERE user.id = :userID AND comment.status != 'Trash'";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with the id parameter
        $requete->bindValue(':userID', $user->getUserId(), \PDO::PARAM_INT);

        // Execute the sql query
        $requete->execute();

        // Retrieves information from DB
        $myComments = $requete->fetchAll();

        foreach ($myComments as $key => $comment) {
            $myComments[$key] = new Comment($comment);
        }

        // Returns the information in array
        return $myComments;
    }

    /**
     * Retrieves the list of comments
     * @return array list of comments
     */
    public function getListOfComments(): array
    {
        // SQL request
        $sql = "
        SELECT
            comment.id AS commentID,
            comment.title,
			comment,
            DATE_FORMAT(comment.creationDate, '%e/%m/%Y') AS date,
            comment.status,
            user.pseudo AS author,
			post.title AS blogTitle,
			post.id AS blog_id
        FROM blog.comment
        INNER JOIN user
            ON user.id = comment.author_id
		INNER JOIN post
            ON post.id = comment.post_id
		WHERE comment.status = 'hold'";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Retrieves information from DB
        $listOfComment = $this->DB
            ->query($sql)
            ->fetchAll();

        foreach ($listOfComment as $key => $comment) {
            $listOfComment[$key] = new Comment($comment);
        }

        // Returns the information in array
        return $listOfComment;
    }

    /**
     * Valid a comment
     * @param  int  $id The comment id
     * @return bool
     */
    public function validComment(int $id):bool
    {
        // SQL request
        $sql = "
        UPDATE blog.comment
        SET comment.status = 'approve'
        WHERE comment.id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query and return bool
        return $requete->execute();
    }

    /**
     * Delete a comment
     * @param int $commentID
     * @return bool
     */
    public function deleteComment(int $commentID):bool
    {
        // SQL request
        $sql = "
        UPDATE blog.comment
        SET comment.status = 'Trash'
        WHERE comment.id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':id', $commentID, \PDO::PARAM_INT);

        // Execute the sql query and return bool
        return $requete->execute();
    }

    /**
     * Verifies that the user is the comment writer
     * @param  int     $commentID The comment id
     * @param  Guest   $userID    The user
     * @return boolean
     */
    public function isWrittenByTheUser(int $commentID, Guest $user):bool
    {
        // SQL request
        $sql = "
        SELECT COUNT(comment.id) AS isWritten
        FROM blog.comment
        WHERE comment.author_id = :userID AND comment.id = :commentID";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':commentID', $commentID, \PDO::PARAM_INT);
        $requete->bindValue(':userID', $user->getUserId(), \PDO::PARAM_INT);

        // Execute the sql query
        $requete->execute();

        // Retrieves information from DB
        $answer = $requete->fetch();

        // Return boolean
        return $answer['isWritten'];
    }
}
