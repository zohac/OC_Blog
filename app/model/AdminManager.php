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
            title,
            post,
			DATE_FORMAT(creationDate, '%e/%m/%Y') AS creationDate,
            DATE_FORMAT(modificationDate, '%e/%m/%Y') AS lastModifDate,
			status,
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
        $myComments = $requete->fetch();

        return $myComments;
    }
}
