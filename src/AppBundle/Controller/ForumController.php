<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entry;
use AppBundle\Entity\Topic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/add", name="forum_add_topic")
     */
    public function addTopicAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $topic = new Topic();
        $form = $this->createForm('AppBundle\Form\TopicType',$topic);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $topic->setHost($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            //dump($topic); die();
            $em->flush();
            return $this->redirectToRoute('forum_index');
        }

       return $this->render('forum/topic_form.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/{$id}/page/1")
     */
    public function showAction()
    {
        
    }
}
