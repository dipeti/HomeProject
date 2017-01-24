<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2016. 12. 30.
 * Time: 23:10
 */

namespace AppBundle\Service;


use AppBundle\Entity\BlogPost;

use AppBundle\Entity\Comment;

use AppBundle\Repository\BlogPostRepository;
use AppBundle\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


class BlogManager implements BlogManagerInterface
{

    protected $blogRepo;
    protected $commentRepo;
    public function __construct(ObjectRepository $blogRepo, ObjectRepository $commentRepo)
    {
        /**
         * @var BlogPostRepository $blogRepo
         * @var CommentRepository $commentRepo
         */
        $this->blogRepo = $blogRepo;
        $this->commentRepo = $commentRepo;
    }

    /**
     * @param BlogPost $post
     */
    public function addPost($post)
    {
       $this->blogRepo->add($post);

    }
    /**
     *
     * @return BlogPost[]
     */
    public function findPostsByQuery($query)
    {
          return $this->blogRepo->findByQuery($query);
    }

    /**
     * @return Paginator
     */
    public function findAllPosts($page, $limit = 5)
    {
       $paginator = $this->blogRepo->findAllDesc();
       $paginator->getQuery()
            ->setFirstResult($limit * ($page-1))
            ->setMaxResults($limit);
        return $paginator;


    }

    /**
     * @param $slug
     * @return BlogPost
     */
    public function findPostBySlug($slug)
    {
       return $this->blogRepo->findBySlug($slug);
    }

    /**
     * @param Comment $comment
     * @return bool
     */
    public function addComment($comment)
    {
        $this->commentRepo->add($comment);
        return true;
    }

    /**
     * @param  Comment $comment
     * @return bool
     */
    public function deleteComment($id)
    {
        $this->commentRepo->delete($id);
        return true;
    }

    public function findAllTags($limit)
    {
       return array_slice($this->blogRepo->findAllTags(),0,$limit);
    }


}