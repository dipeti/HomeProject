<?php

namespace AppBundle\Controller;

use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $posts = $repo->findAll();
        $posts = array_reverse($posts);
        return $this->render(':Blog:home.html.twig',[
            'posts' => $posts,

        ]);
    }
}
