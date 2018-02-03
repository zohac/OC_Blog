<?php
namespace app\model;

use \ZCFram\PDOManager;

/**
 * Manager for the admin section
 */
class AdminManager extends PDOManager
{

    /**
     * List of all posts
     * @return array all posts
     */
    public function getList():array
    {
        // SQL request
        $sql = "
        SELECT
            id,
            title,
			DATE_FORMAT(modificationDate, '%e %M %Y') AS date
        FROM blog.post
        WHERE status != 'Trash'
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
     * Recovers the number of users.
     * @return array number of users
     */
    public function getNumberOfUsers():array
    {
        // SQL request
        $sql = "SELECT COUNT(*) AS numberOfUsers FROM blog.user WHERE user.status='authorized'";

        // Retrieves information from DB
        $numberOfUsers = $this->DB
            ->query($sql)
            ->fetch();

        // Returns the information in array
        return $numberOfUsers;
    }

    /**
     * Recovers the number of posts.
     * @return array number of posts
     */
    public function getNumberOfPosts():array
    {
        // SQL request
        $sql = "SELECT COUNT(*) AS numberOfPosts FROM blog.post WHERE post.status!='Trash'";

        // Retrieves information from DB
        $numberOfPosts = $this->DB
            ->query($sql)
            ->fetch();

        // Returns the information in array
        return $numberOfPosts;
    }

    /**
     * Recovers the number of comments.
     * @return array number of comments
     */
    public function getNumberOfComments():array
    {
        // SQL request
        $sql = "SELECT COUNT(*) AS numberOfComments FROM blog.comment WHERE comment.status!='Trash'";

        // Retrieves information from DB
        $numberOfComments = $this->DB
            ->query($sql)
            ->fetch();

        // Returns the information in array
        return $numberOfComments;
    }

    /**
     * Retrieves information from a post
     * @param  int   $id id of the post
     * @return array     information from a post
     */
    public function getPost(int $id):array
    {
        // SQL request
        $sql = "
        SELECT
            post.id,
            post.title,
            post.post,
			DATE_FORMAT(post.creationDate, '%Y-%m-%e') AS creationDate,
            DATE_FORMAT(post.modificationDate, '%Y-%m-%e') AS modificationDate,
			post.status,
            user.pseudo AS author
        FROM blog.post
        INNER JOIN user
            ON user.id = post.author_id
        WHERE post.id = :id";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with the id parameter
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query
        $requete->execute();

        // Retrieves information from DB
        $PostInfo = $requete->fetch();

        // Returns the information in array
        return $PostInfo;
    }

    /**
     * Retrieves the list of comments
     * @return array list of comments
     */
    public function getListOfComments():array
    {
        // SQL request
        $sql = "
        SELECT
            comment.id,
            comment.title,
			comment,
            DATE_FORMAT(comment.creationDate, '%e/%m/%Y') AS date,
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
        $listOfComments = $this->DB
            ->query($sql)
            ->fetchAll();

        // Returns the information in array
        return $listOfComments;
    }

    /**
     * Retrieves the list of user comments
     * @param  int   $userID the user id
     * @return array         the list of user comments
     */
    public function getMyComments(int $userID):array
    {
        // SQL request
        $sql = "
        SELECT
            comment.id,
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
		WHERE user.id = :userID AND comment.status != 'Trash'";

        //transition from date to french
        $this->DB->query("SET lc_time_names = 'fr_FR'");

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with the id parameter
        $requete->bindValue(':userID', $userID, \PDO::PARAM_INT);

        // Execute the sql query
        $requete->execute();

        // Retrieves information from DB
        $myComments = $requete->fetchAll();

        // Returns the information in array
        return $myComments;
    }

    /**
     * Updating a post
     * @param  array $post
     * @return bool
     */
    public function updatePost(array $post):bool
    {
        // SQL request
        $sql = "
        UPDATE post
        SET post.title = :title, post.post = :post, post.status = :status, post.modificationDate = NOW()
        WHERE id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':title', $post['title'], \PDO::PARAM_STR);
        $requete->bindValue(':post', $post['post'], \PDO::PARAM_STR);
        $requete->bindValue(':status', $post['status'], \PDO::PARAM_STR);
        $requete->bindValue(':id', $post['id'], \PDO::PARAM_INT);

        // Execute the sql query and return bool
        return $requete->execute();
    }

    /**
     * Insert a new post
     * @param  array $post
     * @return bool
     */
    public function insertPost(array $post):bool
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
        $requete->bindValue(':author_id', $post['author_id'], \PDO::PARAM_INT);
        $requete->bindValue(':title', $post['title'], \PDO::PARAM_STR);
        $requete->bindValue(':post', $post['post'], \PDO::PARAM_STR);
        $requete->bindValue(':status', $post['status'], \PDO::PARAM_STR);

        // Execute the sql query and return bool
        return $requete->execute();
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
     * @param  int  $id The comment id
     * @return bool
     */
    public function deleteComment(int $id):bool
    {
        // SQL request
        $sql = "
        UPDATE blog.comment
        SET comment.status = 'Trash'
        WHERE comment.id = :id";

        // Preparing the sql query
        $requete = $this->DB->prepare($sql);

        // Associates a value with parameters
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);

        // Execute the sql query and return bool
        return $requete->execute();
    }

    /**
     * Verifies that the user is the comment writer
     * @param  int     $commentID The comment id
     * @param  int     $userID    The user id
     * @return boolean
     */
    public function isWrittenByTheUser(int $commentID, int $userID):bool
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
        $requete->bindValue(':userID', $userID, \PDO::PARAM_INT);

        // Execute the sql query
        $requete->execute();

        // Retrieves information from DB
        $answer = $requete->fetch();

        // Return boolean
        return $answer['isWritten'];
    }

    /**
     * Find the next id post
     * @return int the next id post
     */
    public function getTheNewPostID():int
    {
        // SQL request
        $sql = "SELECT id FROM `post` ORDER BY id DESC LIMIT 1";

        // Retrieves information from DB
        $id = $this->DB
            ->query($sql)
            ->fetch();

        // The next id
        $id = 1 + (int)$id['id'];

        // return the next id post
        return $id;
    }
}
