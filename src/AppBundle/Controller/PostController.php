<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use AppBundle\Service\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/post/{slug}", name="readPost")
     */
    public function readAction(Request $request, $slug)
    {
        $post = $this->manager->findPostById($slug);
        $recents = $this->manager->findAllPosts();
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $this->manager->persistComment($comment);
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
     * @Route("/delete/comment/{id}", name="deleteComment")
     */
    public function deleteCommentAction(Request $request, $id){

        $this->manager->deleteComment($id);
        return $this->redirectToReferer($request);

    }
    /**
     * @Route("/edit/comment/{id}", name="editComment")
     */
    public function editCommentAction(Request $request, $id){

       $repo = $this->getDoctrine()->getRepository('AppBundle:Comment');
        $toBeEdited = $repo->find($id);

       $form = $this->createFormBuilder($toBeEdited)
           ->add('_content')
           ->add('save', SubmitType::class)
           ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->manager->persistComment($toBeEdited);

            return  $this->redirect($this->generateUrl('readPost',['slug'=>$toBeEdited->getPost()->getId()])."#comments");
        }

        return $this->render('Blog/comment_form.html.twig', [
            'form' => $form->createView(),
            'original' => $toBeEdited->getContent(),
        ]);
    }

    private function redirectToReferer(Request $request){

        return $this->redirect($request->headers->get('referer')."#comments");
    }


}
