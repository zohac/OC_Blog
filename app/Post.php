<?php
namespace app;

use \ZCFram\Hydrator;

/**
 *
 */
class Post
{
    use Hydrator;

    protected $postID;

    protected $author;

    protected $title;

    protected $chapo;

    protected $post;

    protected $creationDate;

    protected $modificationDate;

    protected $status;

    protected $imgPath;

    protected $day;

    protected $monthYear;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function setPostID(int $id)
    {
        $this->postID = $id;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setChapo(string $chapo)
    {
        $this->chapo = $chapo;
    }

    public function setPost(string $post)
    {
        $this->post = $post;
    }

    public function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function setModificationDate(string $modificationDate)
    {
        $this->modificationDate = $modificationDate;
        $date = \explode('-', $modificationDate);
        if (\count($date) > 1) {
            $this->day = $date['0'];
            $this->monthYear = $date['1'];
        }
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function setImgPath(int $imgPath)
    {
        if (file_exists(__DIR__.'/../web/upload/blog-'.$imgPath.'.jpg')) {
            $imgPath = '/upload/blog-'.$imgPath.'.jpg';
        } elseif (file_exists(__DIR__.'/../web/upload/blog-'.$imgPath.'.png')) {
            $imgPath = '/upload/blog-'.$imgPath.'.png';
        } else {
            $imgPath = '/upload/default.jpg';
        }
        $this->imgPath = $imgPath;
    }

    public function getPostID()
    {
        return $this->postID;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getChapo()
    {
        return $this->chapo;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getModificationDate()
    {
        return $this->modificationDate;
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

    public function getImgPath()
    {
        return $this->imgPath;
    }
}
