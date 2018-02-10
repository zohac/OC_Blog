<?php
namespace app\Entity;

use \ZCFram\Hydrator;

/**
 * Class representing a comment
 */
class Comment
{
    // Using the Hydrator trait
    use Hydrator;

    /**
     * The id of the comment
     * @var int
     */
    protected $commentID;

    /**
     * The identifier of the post to which the comment is attached
     * @var string
     */
    protected $idPost;

    /**
     * The identifier of the author who write the comment
     * @var string
     */
    protected $author;

    /**
     * The comment
     * @var string
     */
    protected $comment;

    /**
     * The creation date of the comment
     * @var string
     */
    protected $creationDate;

    /**
     * The day on which the commentary was written
     * @var string
     */
    protected $day;

    /**
     * The month and the year on which the commentary was written
     * @var string
     */
    protected $monthYear;

    /**
     * The status of the comment
     * @var string
     */
    protected $status;

    /**
     * Construction and hydration of the commentary
     * @var array $data
     */
    public function __construct(array $data = [])
    {
        // If there are data, the commentary is hydrated.
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * Storing the comment id
     * @var int $id
     */
    public function setCommentID(int $id)
    {
        $this->commentID = $id;
    }

    /**
     * Storing the post id
     * @var int $id
     */
    public function setIdPost(int $id)
    {
        $this->idPost = $id;
    }

    /**
     * Storing the author
     * @var string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * Storing the comment
     * @var string $comment
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Storing the creation Date, Day and MonthYear
     * @var string $creationDate
     */
    public function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
        $date = \explode('-', $creationDate);
        $this->day = $date['0'];
        $this->monthYear = $date['1'];
    }

    /**
     * Storing the status
     * @var string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * Get the id of the comment
     * @return int
     */
    public function getCommentID(): int
    {
        return $this->commentID;
    }

    /**
     * Get the id of the post
     * @return int
     */
    public function getIdPost(): int
    {
        return $this->idPost;
    }

    /**
     * Get the author of the comment
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get the comment
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Get the creation date of the comment
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * Get the day of the comment creation date
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * Get the month and the year of the comment creation date
     * @return string
     */
    public function getMonthYear(): string
    {
        return $this->monthYear;
    }

    /**
     * Get the status of the comment
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
