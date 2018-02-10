<?php
namespace app\Entity;

use \ZCFram\Hydrator;

/**
 * Class representing a post
 */
class Post
{
    // Using the Hydrator trait
    use Hydrator;

    /**
     * The id of the post
     * @var int
     */
    protected $postID;

    /**
     * The author of the post
     * @var sting
     */
    protected $author;

    /**
     * The title of the post
     * @var sting
     */
    protected $title;

    /**
     * The chapo of the post
     * @var sting
     */
    protected $chapo;

    /**
     * The post
     * @var sting
     */
    protected $post;

    /**
     * The creation date of the post
     * @var sting
     */
    protected $creationDate;

    /**
     * The modification date of the post
     * @var sting
     */
    protected $modificationDate;

    /**
     * The status of the post
     * @var sting
     */
    protected $status;

    /**
     * The path to the image attached to the post
     * @var sting
     */
    protected $imgPath;

    /**
     * The day on which the post was written
     * @var string
     */
    protected $day;

    /**
     * The day on which the post was written
     * @var string
     */
    protected $monthYear;

    /**
     * The date of the post formatted for posting
     * @var string
     */
    protected $datePost;

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
    public function setPostID(int $id)
    {
        $this->postID = $id;
    }

    /**
     * Storing the author of the post
     * @var string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * Storing the title of the post
     * @var string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Storing the chapo of the post
     * @var string $chapo
     */
    public function setChapo(string $chapo)
    {
        $this->chapo = $chapo;
    }

    /**
     * Storing the post
     * @var string $post
     */
    public function setPost(string $post)
    {
        $this->post = $post;
    }

    /**
     * Storing the creation Date of the post
     * @var string $creationDate
     */
    public function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * Storing the modification Date, Day and MonthYear of the post
     * @var string $modificationDate
     */
    public function setModificationDate(string $modificationDate)
    {
        $this->modificationDate = $modificationDate;
        $date = \explode('-', $modificationDate);
        if (\count($date) > 1) {
            $this->day = $date['2'];
            $this->monthYear = $date['1'].' '.$date['0'];
        }
    }

    /**
     * Storing the modification Date, Day and MonthYear of the post
     * @var string $modificationDate
     */
    public function setDatePost(string $datePost)
    {
        $this->datePost = $datePost;
    }

    /**
     * Storing the status of the post
     * @var string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }
    /**
     * Storing the image path of the post
     * @var string $imgPath
     */
    public function setImgPath(int $imgPath)
    {
        // If the image exists in jpg or png format, we store the path
        if (file_exists(__DIR__.'/../../web/upload/blog-'.$imgPath.'.jpg')) {
            $imgPath = '/upload/blog-'.$imgPath.'.jpg';
        } elseif (file_exists(__DIR__.'/../../web/upload/blog-'.$imgPath.'.png')) {
            $imgPath = '/upload/blog-'.$imgPath.'.png';
        } else {
            // Else a default path
            $imgPath = '/upload/default.jpg';
        }
        $this->imgPath = $imgPath;
    }

    /**
     * Get the comment id
     * @return int
     */
    public function getPostID(): int
    {
        return $this->postID;
    }

    /**
     * Get the author
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get the chapo
     * @return string
     */
    public function getChapo(): string
    {
        return $this->chapo;
    }

    /**
     * Get the title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the post
     * @return string
     */
    public function getPost(): string
    {
        return $this->post;
    }

    /**
     * Get the creation date
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * Get the modification date
     * @return string
     */
    public function getModificationDate(): string
    {
        return $this->modificationDate;
    }

    /**
     * Get the day of the post date
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * Get the month and the year of the post date
     * @return string
     */
    public function getMonthYear(): string
    {
        return $this->monthYear;
    }

    /**
     * Storing the modification Date, Day and MonthYear of the post
     * @return string
     */
    public function getDatePost(): string
    {
        return $this->datePost;
    }

    /**
     * Get the status of the post
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get the image path of the post
     * @return string
     */
    public function getImgPath(): string
    {
        return $this->imgPath;
    }
}
