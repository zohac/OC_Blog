<?php
namespace app\model;

use \ZCFram\Manager;

/**
 *
 */
class AdminManager extends Manager
{

    public function getList()
    {
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

        return $listPosts;
    }

    public function getNumberOfUsers()
    {
        $sql = "SELECT COUNT(*) AS numberOfUsers FROM blog.user";

        $numberOfUsers = $this->DB
            ->query($sql)
            ->fetch();

        return $numberOfUsers;
    }

    public function getNumberOfPosts()
    {
        // TODO : Modifier la requète pour les post supprimé
        $sql = "SELECT COUNT(*) AS numberOfPosts FROM blog.post";

        $numberOfPosts = $this->DB
            ->query($sql)
            ->fetch();

        return $numberOfPosts;
    }

    public function getNumberOfComments()
    {
        $sql = "SELECT COUNT(*) AS numberOfComments FROM blog.comment";

        $numberOfComments = $this->DB
            ->query($sql)
            ->fetch();

        return $numberOfComments;
    }

    public function getPost(int $id)
    {
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
		WHERE user.id = :userID";

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
        $sql = "
        UPDATE blog.comment
        SET comment.status = 'Trash'
        WHERE comment.id = :id";

        $requete = $this->DB->prepare($sql);
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        return $requete->execute();
    }
}
