<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class AdminManager extends PDOManager
{

    public function getList()
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
        $listPosts = $this->DB
            ->query($sql)
            ->fetchAll();

        // Returns the information in array
        return $listPosts;
    }

    public function getNumberOfUsers()
    {
        // SQL request
        $sql = "SELECT COUNT(*) AS numberOfUsers FROM blog.user";

        // Retrieves information from DB
        $numberOfUsers = $this->DB
            ->query($sql)
            ->fetch();

        // Returns the information in array
        return $numberOfUsers;
    }

    public function getNumberOfPosts()
    {
        // TODO : Modifier la requète pour les post supprimé
        // SQL request
        $sql = "SELECT COUNT(*) AS numberOfPosts FROM blog.post";

        // Retrieves information from DB
        $numberOfPosts = $this->DB
            ->query($sql)
            ->fetch();

        // Returns the information in array
        return $numberOfPosts;
    }

    public function getNumberOfComments()
    {
        // SQL request
        $sql = "SELECT COUNT(*) AS numberOfComments FROM blog.comment";

        $numberOfComments = $this->DB
            ->query($sql)
            ->fetch();

        // Returns the information in array
        return $numberOfComments;
    }

    public function getPost(int $id)
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
        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        $requete->execute();
        $PostInfo = $requete->fetch();

        return $PostInfo;
    }

    public function getListOfComments()
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
        $listOfComments = $this->DB
            ->query($sql)
            ->fetchAll();

        return $listOfComments;
    }

    public function getMyComments(int $userID)
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
        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':userID', $userID, \PDO::PARAM_INT);
        $requete->execute();
        $myComments = $requete->fetchAll();

        return $myComments;
    }

    public function updatePost(array $post)
    {
        // SQL request
        $sql = "
        UPDATE post
        SET post.title = :title, post.post = :post, post.status = :status, post.modificationDate = NOW()
        WHERE id = :id";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':title', $post['title'], \PDO::PARAM_STR);
        $requete->bindValue(':post', $post['post'], \PDO::PARAM_STR);
        $requete->bindValue(':status', $post['status'], \PDO::PARAM_STR);
        $requete->bindValue(':id', $post['id'], \PDO::PARAM_INT);
        return $requete->execute();
    }

    public function insertPost(array $post)
    {
        // SQL request
        $sql = "
        INSERT INTO `blog`.`post`
            (author_id, title, post, creationDate, modificationDate, status)
        VALUES
            (:author_id, :title, :post, NOW(), NOW(), :status)";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':author_id', $post['author_id'], \PDO::PARAM_INT);
        $requete->bindValue(':title', $post['title'], \PDO::PARAM_STR);
        $requete->bindValue(':post', $post['post'], \PDO::PARAM_STR);
        $requete->bindValue(':status', $post['status'], \PDO::PARAM_STR);
        return $requete->execute();
    }

    public function deletePost(int $id)
    {
        // SQL request
        $sql = "
        UPDATE post
        SET status = 'Trash', modificationDate = NOW()
        WHERE id = :id";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        return $requete->execute();
    }

    public function validComment(int $id)
    {
        // SQL request
        $sql = "
        UPDATE blog.comment
        SET comment.status = 'approve'
        WHERE comment.id = :id";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        return $requete->execute();
    }

    public function deleteComment(int $id)
    {
        // SQL request
        $sql = "
        UPDATE blog.comment
        SET comment.status = 'Trash'
        WHERE comment.id = :id";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        return $requete->execute();
    }

    public function isWrittenByTheUser(int $commentID, int $userID)
    {
        // SQL request
        $sql = "
        SELECT COUNT(comment.id) AS isWritten
        FROM blog.comment
        WHERE comment.author_id = :userID AND comment.id = :commentID";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':commentID', $commentID, \PDO::PARAM_INT);
        $requete->bindValue(':userID', $userID, \PDO::PARAM_INT);
        $requete->execute();
        $answer = $requete->fetch();
        return $answer['isWritten'];
    }

    public function getTheNewPostID()
    {
        // SQL request
        $sql = "SELECT id FROM `post` ORDER BY id DESC LIMIT 1";

        $requete = $this->DB->query($sql);
        $requete->execute();
        $id = $requete->fetch();
        $id = 1 + (int)$id['id'];
        return $id;
    }
}
