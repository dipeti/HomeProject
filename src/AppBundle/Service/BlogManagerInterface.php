<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2016. 12. 30.
 * Time: 22:53
 */

namespace AppBundle\Service;


use AppBundle\Entity\BlogPost;
use AppBundle\Entity\Comment;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface BlogManagerInterface
{
    /**
     * @param $query string
     * @return BlogPost[]
     */
    public function findPostsByQuery($query);

    /**
     * @param  integer $page
     * @param  integer $limit
     * @return Paginator
     */
    public function findAllPosts($page, $limit);

    /**
     * @param $slug string
     * @return BlogPost
     */
    public function findPostBySlug($slug);

    /**
     * @param Comment $comment
     * @return bool
     */
    public function addComment($comment);

    /**
     * @param  Comment $comment
     * @return bool
     */
    public function deleteComment($comment);

    /**
     * @param array $data
     * @return mixed
     */
    public function addPost($data);

    public function findAllTags($limit);


}