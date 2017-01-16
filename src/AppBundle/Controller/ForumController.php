<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ForumController
 * @package AppBundle\Controller
 * @Route("forum")
 */
class ForumController extends Controller
{
    /**
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="forum_index")
     */
    public function indexAction()
    {
        return $this->render(':forum:index.html.twig');
    }
}
