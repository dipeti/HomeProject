<?php

namespace AppBundle\Controller;


use AppBundle\Service\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
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
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this->manager= $this->get('app.blog_manager');
        $query = $request->get('q');
        if(null!==$query){

             $posts = $this->manager->findPostsByQuery($query);
        }else{
        $posts = $this->manager->findAllPosts();

        }

        return $this->render(':Blog:home.html.twig',[
            'posts' => $posts,
            'error'=> count($posts) ? '' : 'Unfortunately there is no matching result!',
            'randomtags'=> array_slice($this->manager->findAllTags(),0,5)

        ]);
    }

}
