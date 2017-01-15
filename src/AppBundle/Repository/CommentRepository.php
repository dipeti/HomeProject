<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CommentRepository extends EntityRepository
{

    public function delete($id)
    {
        $comment = $this->find($id);
        $em = $this->getEntityManager();
        $em ->remove($comment);
        $em->flush();

    }

    public function add($comment)
    {
        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();
    }
}
