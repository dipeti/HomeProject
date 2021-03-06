<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2016. 12. 30.
 * Time: 17:44
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 * @ORM\Table(name="comments")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $_id;

    /**
     * @ORM\Column(type="text", name="content")
     * @Assert\Length(min="3", minMessage="The comment must contain at least 3 characters")
     */
    private $_content;

    /**
     * @ORM\Column(type="datetime", name="posted_at")
     */
    private $_postedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BlogPost", inversedBy="_comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $_post;

    /**
     * @ORM\PrePersist()
     */
    public function setPostedAt()
    {   if($this->_postedAt===null)
        $this->_postedAt = new \DateTime();
    }


    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
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
     * Get postedAt
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->_postedAt;
    }

    /**
     * Set post
     *
     * @param \AppBundle\Entity\BlogPost $post
     *
     * @return Comment
     */
    public function setPost(\AppBundle\Entity\BlogPost $post = null)
    {
        $this->_post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\BlogPost
     */
    public function getPost()
    {
        return $this->_post;
    }
}
