<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $query = $request->get('q');
        if(null!==$query){
            $posts = $repo->findBy(['_title'=>$query]);
        }else{
        $posts = $repo->findAll();
        }
        $posts = array_reverse($posts);
        return $this->render(':Blog:home.html.twig',[
            'posts' => $posts,
            'error'=> count($posts) ? '' : 'Unfortunately there is no matching result!'
        ]);
    }

}
