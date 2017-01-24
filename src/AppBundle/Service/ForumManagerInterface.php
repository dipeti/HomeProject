<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2017. 01. 24.
 * Time: 20:20
 */

namespace AppBundle\Service;


use AppBundle\Entity\Entry;
use AppBundle\Entity\Topic;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

interface ForumManagerInterface
{
    /**
     * @return Topic[]
     */
    public function getAllTopics();

    /**
     * @param Topic $topic
     * @param $offset integer
     * @param $limit  integer
     * @return Entry[]
     */
    public function getEntriesByOffset(Topic $topic, $offset,$limit);

    /**
     * @return ArrayCollection
     */
    public function getAllEntries();

    /**
     * @param Topic $topic
     * @param Entry $entry
     * @return mixed
     */
    public function addEntry(Entry $entry, Topic $topic = null, User $user = null);

    /**
     * @param Entry $entry
     * @return mixed
     */
    public function deleteEntry(Entry $entry);

    /**
     * @param Topic $topic
     * @param User $user
     * @return mixed
     */
    public function addTopic(Topic $topic, User $user);

    /**
     * @param $topic Topic
     * @return mixed
     */
    public function deleteTopic(Topic $topic);



}