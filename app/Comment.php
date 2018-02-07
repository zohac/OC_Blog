<?php
namespace app;

use \ZCFram\Hydrator;

/**
 *
 */
class Comment
{
    use Hydrator;

    protected $commentID;

    protected $idPost;

    protected $author;

    protected $comment;

    protected $creationDate;

    protected $day;

    protected $monthYear;

    protected $status;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function setCommentID(int $id)
    {
        $this->commentID = $id;
    }

    public function setIdPost(int $id)
    {
        $this->idPost = $id;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    public function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
        $date = \explode('-', $creationDate);
        $this->day = $date['0'];
        $this->monthYear = $date['1'];
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getCommentID()
    {
        return $this->commentID;
    }

    public function getIdPost()
    {
        return $this->idPost;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function getMonthYear()
    {
        return $this->monthYear;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
