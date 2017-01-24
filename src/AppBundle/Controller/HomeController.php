<?php

namespace AppBundle\Controller;


use AppBundle\Form\PostType;
use AppBundle\Service\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @var BlogManager $manager
     */
    protected $manager;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->manager= $this->get('app.blog_manager');
    }

    /**
     *
     *
     * @Route("/",name="homepage", defaults={"page" = 1})
     * @Route("/page/{page}", name="homepage_paginated", requirements={"page" : "\d+"})
     */
    public function listAction(Request $request, $page)
    {

        $query = $request->get('q');
        if(null!==$query){
            $posts = $this->manager->findPostsByQuery($query);
            $maxpage=1;
        }else{
            $limit = 2;
            $posts = $this->manager->findAllPosts($page,$limit);
            $maxpage = ceil($posts->count()/$limit);

        }

        return $this->render(':Blog:home.html.twig',[
            'posts' => $posts,
            'error'=> count($posts) ? '' : 'Unfortunately there is no matching result!',
            'randomtags'=> $this->manager->findAllTags(10),
            'maxpage' => $maxpage,
            'currentpage' => $page,

        ]);
    }


    /**
     * @Route("/about" ,name="about")
     */
    public function aboutAction()
    {
       return $this->render('about/index.html.twig');
    }



}
