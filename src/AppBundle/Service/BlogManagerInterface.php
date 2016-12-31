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

interface BlogManagerInterface
{
    /**
     * @param $query string
     * @return BlogPost[]
     */
    public function findPostsByQuery($query);

    /**
     * @return BlogPost[]
     */
    public function findAllPosts();

    /**
     * @param $slug integer
     * @return BlogPost
     */
    public function findPostById($slug);

    /**
     * @param Comment $comment
     * @return bool
     */
    public function persistComment($comment);

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


}