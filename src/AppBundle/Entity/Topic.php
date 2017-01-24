<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2017. 01. 18.
 * Time: 22:23
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="topics")
 * @ORM\HasLifecycleCallbacks()
 */
class Topic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $title;
    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;
    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $description;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="host_id", referencedColumnName="id")
     */
    private $host;
    /**
     * @ORM\Column(type="datetime")
     */
    private $lastEntryDate;
    /**
     * @var array $entries
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Entry", mappedBy="topic", cascade={"persist"})
     * @Assert\Valid()
     *
     */
    private $entries;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @ORM\PrePersist()
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return \DateTime
     */
    public function getLastEntryDate()
    {
        if($this->entries->count()>0)
        return $this->entries->last()->getCreatedAt();
        return null;
    }

    /**
     * @param mixed $lastEntryDate
     * @ORM\PrePersist()
     */
    public function setLastEntryDate($lastEntryDate)
    {
        $this->lastEntryDate = new \DateTime();
    }

    /**
     * @return ArrayCollection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param int $offset
     * @return ArrayCollection
     */
    public function getEntriesWithOffset($offset=0, $limit=10)
    {
        return array_slice($this->entries->toArray(),$offset,$limit);
    }

    /**
     * @param mixed $entries
     */
    public function setEntries($entries)
    {
        $this->entries = $entries;
    }

    /**
     * @return string
     */
    public function getLastUserToReply(){
        if($this->entries->count()>0){
            return $this->entries->last()->getUser()->getUsername();
        }
        return null;
    }





    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    /**
     * Add entry
     *
     * @param \AppBundle\Entity\Entry $entry
     *
     * @return Topic
     */
    public function addEntry(\AppBundle\Entity\Entry $entry)
    {
        $entry->setTopic($this);
        $entry->setNumber($this->entries->count()>0 ? $this->entries->last()->getNumber()+1 : 1);
        $this->entries->add($entry);
        return $this;
    }

    /**
     * Remove entry
     *
     * @param \AppBundle\Entity\Entry $entry
     */
    public function removeEntry(\AppBundle\Entity\Entry $entry)
    {
        $this->entries->removeElement($entry);
    }
}
