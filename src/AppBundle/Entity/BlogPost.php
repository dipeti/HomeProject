<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2016. 12. 30.
 * Time: 0:33
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BlogPost
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="blogposts")
 * @ORM\HasLifecycleCallbacks()
 */
class BlogPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $_id;

    /**
     * @ORM\Column(type="string", length=64, unique=true, name="title")
     */
    private $_title;

    /**
     * @ORM\Column(type="text", name="content")
     */
    private $_content;

    /**
     * @ORM\Column(type="string", name="author")
     */
    private $_author;

    /**
     * @ORM\Column(type="datetime", name="posted_at")
     */
    private $_postedAt;

    /**
     * @ORM\Column(type="array", name="tags")
     */
    private $_tags;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="_post")
     *
     */
    private $_comments;

    /**
     * @ORM\Column(type="string", nullable=true, name="img_uri")
     */
    private $_imgURI;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return BlogPost
     */
    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return BlogPost
     */
    public function setContent($content)
    {
        $this->_content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return BlogPost
     */
    public function setAuthor($author)
    {
        $this->_author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * Set postedAt
     * @ORM\PrePersist
     * @param \DateTime $postedAt
     *
     * @return BlogPost
     */
    public function setPostedAt($postedAt)
    {
        if($this->_postedAt === null){
            $this->_postedAt = new \DateTime();
            return $this;
        }
        $this->_postedAt = $postedAt;

        return $this;
    }

    /**
     * Get postedAt
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->_postedAt;
    }

    /**
     * Set tags
     *
     * @param array $tags
     *
     * @return BlogPost
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * Set comments
     *
     * @param array $comments
     *
     * @return BlogPost
     */
    public function setComments($comments)
    {
        $this->_comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return array
     */
    public function getComments()
    {
        return $this->_comments;
    }

    /**
     * Set imgURI
     *
     * @param string $imgURI
     *
     * @return BlogPost
     */
    public function setImgURI($imgURI)
    {
        $this->_imgURI = $imgURI;

        return $this;
    }

    /**
     * Get imgURI
     *
     * @return string
     */
    public function getImgURI()
    {
        return $this->_imgURI;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return BlogPost
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->_comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->_comments->removeElement($comment);
    }
}
