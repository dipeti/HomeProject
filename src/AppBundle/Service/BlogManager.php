<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2016. 12. 30.
 * Time: 23:10
 */

namespace AppBundle\Service;


use AppBundle\Entity\BlogPost;
use AppBundle\Entity\BlogPostRepository;
use AppBundle\Entity\Comment;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;


class BlogManager implements BlogManagerInterface
{
    /**
     * @var BlogPostRepository $repo
     * @var EntityManager $em
     */
    protected $repo;
    protected $em;
    public function __construct(ObjectRepository $blogRepo, EntityManagerInterface $em)
    {
        $this->repo = $blogRepo;
        $this->em = $em;
    }

    public function addPost($data)
    {
        // TODO: Implement addPost() method.
    }
    /**
     *
     * @return BlogPost[]
     */
    public function findPostsByQuery($query)
    {
          return $this->repo->findByQuery($query);
    }

    /**
     * @return BlogPost[]
     */
    public function findAllPosts()
    {
       return $this->repo->findAllDesc();
    }

    /**
     * @param $slug
     * @return BlogPost
     */
    public function findPostById($slug)
    {
       return $this->repo->find($slug);
    }

    /**
     * @param Comment $comment
     * @return bool
     */
    public function persistComment($comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
        return true;
    }

    /**
     * @param  Comment $comment
     * @return bool
     */
    public function deleteComment($id)
    {
        $commentRepo = $this->em->getRepository('AppBundle:Comment');
        $toBeDeleted = $commentRepo->find($id);
        $this->em->remove($toBeDeleted);
        $this->em->flush();
        return true;
    }

    public function findAllTags()
    {
       return $this->repo->findAllTags();
    }


}