<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UploadedFile
 *
 * @ORM\Table(name="uploaded_files")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UploadedFileRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UploadedFile
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @Assert\File(maxSize="5M")
     */
    private $content;

    /**
     * @ORM\Column(name="uri", type="string")
     *
     */
    private $uri;
    /**
     * @ORM\Column(name="absolute_path", type="string")
     */
    private $absolutePath;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=true)
     * @Assert\Regex(pattern = "/[\s]+/", htmlPattern=false, match=false, message="Filename must not contain whitespaces!")
     */
    private $fileName;
    /**
     * @ORM\Column(type="string")
     */
    private $extension;

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }
    /**
     * @return mixed
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * @param mixed $absolutePath
     */
    public function setAbsolutePath($absolutePath)
    {
        $this->absolutePath = $absolutePath;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }



    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        unlink($this->getAbsolutePath());
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return int|null
     */
    public function getSize()
    {
        if($this->size<1000000){
       $kb = round($this->size/(1000),2);
        return "{$kb}".' KB';
        }
        $mb = round($this->size/(1000000),2);
        return "{$mb}".' MB';

    }

    /**
     * @param int|null $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * The file size provided by the uploader.
     *
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    private $size;
    /**
     * @var int
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views=0;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return UploadedFile
     */
    public function setViews($views)
    {
        $this->views += $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return UploadedFile
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}

