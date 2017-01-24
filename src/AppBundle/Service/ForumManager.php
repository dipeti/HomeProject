<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2017. 01. 24.
 * Time: 20:19
 */

namespace AppBundle\Service;


use AppBundle\Entity\Entry;
use AppBundle\Entity\Topic;
use AppBundle\Entity\User;
use AppBundle\Repository\EntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ForumManager implements ForumManagerInterface
{

    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * @var EntityRepository $topicRepo
     */
    private $topicRepo;
    /**
     * @var EntryRepository $entryRepo
     */
    private $entryRepo;

    /**
     * ForumManager constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->topicRepo=$em->getRepository('AppBundle:Topic');
        $this->entryRepo=$em->getRepository('AppBundle:Entry');

    }

    /**
     * @return Topic[]
     */
    public function getAllTopics()
    {
       return $this->topicRepo->findBy([],['lastEntryDate'=>'DESC']);
    }

    /**
     * @param Topic $topic
     * @param $offset integer
     * @param $limit  integer
     * @return ArrayCollection
     */
    public function getEntriesByOffset(Topic $topic, $offset, $limit)
    {
       return $topic->getEntries()->slice($offset,$limit);
    }

    /**
     * @return array
     */
    public function getAllEntries()
    {
       return $this->entryRepo->findAll();
    }

    /**
     * @param Entry $entry
     * @param Topic|null $topic
     * @param User|null $user
     */
    public function addEntry(Entry $entry, Topic $topic = null, User $user = null)
    {
        if($topic!==null) $topic->addEntry($entry);
        if($user!==null)  $entry->setUser($user);

        $this->em->persist($entry);
        $this->em->flush();
    }

    /**
     * @param Entry $entry
     * @return mixed
     */
    public function deleteEntry(Entry $entry)
    {
        $this->em->remove($entry);
        $this->em->flush();
    }

    /**
     * @param Topic $topic
     * @param User $user
     */
    public function addTopic(Topic $topic, User $user=null)
    {
        if($user!==null)
        $topic->setHost($user);
        $this->em->persist($topic);
        $this->em->flush();
    }

    /**
     * @param $topic Topic
     */
    public function deleteTopic(Topic $topic)
    {
        $this->em->remove($topic);
        $this->em->flush();
    }
}