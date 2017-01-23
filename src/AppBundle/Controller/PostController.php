<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogPost;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use AppBundle\Form\PostType;
use AppBundle\Service\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostController
 * @package AppBundle\Controller
 * @Route("/blog")
 */
class PostController extends Controller
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
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{slug}", name="readPost")
     */
    public function readAction(Request $request, $slug)
    {
        $post = $this->manager->findPostBySlug($slug);
        if(!$post) throw $this->createNotFoundException();
        $recents = $this->manager->findAllPosts(1);
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $this->manager->addComment($comment);
            return $this->redirect($this->generateUrl('readPost',['slug'=> $slug])."#comments");
        }
        return $this->render(':Blog:post.html.twig', array(
            'post' => $post,
            'recents' => $recents,
            'comments' => $post->getComments(),
            'comment_form' => $form->createView()
        ));
    }

    /**
     * @Route("/add/new", name="addPost")
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',null,"You do NOT possess Admin priviliges.");
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser()->getUsername());
            $img  = $post->getImgURI();
            if($img){
                $imgname = md5(uniqid()).'.'.$img->guessExtension();
                $folder = $this->getParameter('images_dir');
                $img->move($folder,$imgname);
                $post->setImgURI($imgname);
            }
            $tags = explode(',',str_replace(' ','',$post->getTags()));
            $post->setTags($tags);
            $this->manager->addPost($post);
            return $this->redirectToRoute('readPost', ['slug' => $post->getId()]);
        }
        return $this->render(":Blog:blogpost_form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    private function redirectToReferer(Request $request){

        return $this->redirect($request->headers->get('referer')."#comments");
    }

    /**
     * @Route("/delete/comment/{id}", name="deleteComment")
     */
    public function deleteCommentAction(Request $request, $id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN',null,"You do NOT possess Admin priviliges.");
        $this->manager->deleteComment($id);
        return $this->redirectToReferer($request);

    }
    /**
     * @Route("/edit/comment/{id}", name="editComment")
     */
    public function editCommentAction(Request $request, $id){

        $this->denyAccessUnlessGranted('ROLE_ADMIN',null,"You do NOT possess Admin priviliges.");

       $repo = $this->getDoctrine()->getRepository('AppBundle:Comment');
        $toBeEdited = $repo->find($id);

       $form = $this->createFormBuilder($toBeEdited)
           ->add('_content')
           ->add('save', SubmitType::class)
           ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->manager->addComment($toBeEdited);

            return  $this->redirect($this->generateUrl('readPost',['slug'=>$toBeEdited->getPost()->getId()])."#comments");
        }

        return $this->render('Blog/comment_form.html.twig', [
            'form' => $form->createView(),
            'original' => $toBeEdited->getContent(),
        ]);

    }



}
