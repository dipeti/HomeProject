<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/post/{slug}", name="readPost")
     */
    public function readAction(Request $request, $slug)
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($this->getDoctrine()->getRepository('AppBundle:BlogPost')->find($slug));
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('readPost',['slug'=> $slug])."#comments");
        }
        $repo =$this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $post = $repo ->find($slug);
        $recents = $repo->findAll();
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
    public function deleteCommentAction($id){

       $repo = $this->getDoctrine()->getRepository('AppBundle:Comment');
       $toBeDeleted = $repo->find($id);

        $slug = $toBeDeleted->getPost()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($toBeDeleted);
        $em->flush();
       return $this->redirect($this->generateUrl('readPost', ['slug'=> $slug])."#comments");
    }
    /**
     * @Route("/edit/comment/{id}", name="editComment")
     */
    public function editCommentAction(Request $request, $id){

       $repo = $this->getDoctrine()->getRepository('AppBundle:Comment');
        $toBeEdited = $repo->find($id);
        $slug = $toBeEdited->getPost()->getId();
       $form = $this->createFormBuilder($toBeEdited)
           ->add('_content')
           ->add('save', SubmitType::class)
           ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($toBeEdited);
            $em->flush();
            return  $this->redirectToRoute('readPost',['slug' => $slug]);
        }

        return $this->render('Blog/comment_form.html.twig', [
            'form' => $form->createView(),
            'original' => $toBeEdited->getContent(),
        ]);
    }


}
