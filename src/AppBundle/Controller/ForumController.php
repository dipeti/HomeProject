<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entry;
use AppBundle\Entity\Topic;
use AppBundle\Entity\User;
use AppBundle\Service\ForumManager;
use AppBundle\Service\ForumManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
     * @var ForumManager $forumManager
     */
    private $forumManager;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->forumManager = $this->get('app.forum_manager');
    }


    /**
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="forum_index")
     */
    public function indexAction()
    {
        $topics = $this->forumManager->getAllTopics();
        return $this->render(':forum:index.html.twig',[
            'topics' => $topics,
        ]);
    }

    /**
     * @Route("/{slug}", name="forum_show_topic")
     */
    public function showTopicAction(Topic $topic, Request $request)
    {
        if($request->query->has('page')){
            $page = $request->query->get('page');
        }else
            $page = 1;
        if($request->query->has('limit')){
            $limit = $request->query->get('limit');
        }else
            $limit = 10;
        $offset = ($page-1)*$limit;
        $entries = $this->forumManager->getEntriesByOffset($topic, $offset,$limit);
        $total_entries_count=$topic->getEntries()->count();
        $pages = $total_entries_count ? ceil($total_entries_count/$limit) : 1;
        //dump($entries);  dump($total_entries_count); dump($pages); die();
        return $this->render('forum/entries.html.twig',[
            'topic' => $topic,
            'entries' => $entries,
            'pages' => $pages,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    /**
     * @param Topic $topic
     * @Route("/reply/{slug}", name="forum_leave_reply")
     */
    public function leaveReplyAction(Topic $topic, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entry = new Entry();
        $form = $this->createForm('AppBundle\Form\EntryType', $entry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $this->forumManager->addEntry($entry, $topic,$this->getUser());
            $redirectURL = $request->get('_target_path', $this->generateUrl('forum_show_topic',['slug' => $topic->getSlug()]));
            return $this->redirect($redirectURL);
        }
        return $this->render(':forum:entry_form.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Entry $entry
     * @Route("/reply/edit/{id}", name="forum_edit_reply")
     */
    public function editReplyAction(Entry $entry, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $entry);
        $form = $this->createForm('AppBundle\Form\EntryType', $entry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->forumManager->addEntry($entry);
            return $this->redirectToRoute('forum_show_topic', ['slug' => $entry->getTopic()->getSlug()]);
        }
        return $this->render(':forum:entry_form.html.twig',[
            'form' => $form->createView(),
            'original' => $entry->getContent(),
        ]);
    }

    /**
     * @Route("/reply/delete/{id}", name="forum_delete_reply")
     */
    public function deleteReplyAction(Entry $entry)
    {
        $this->forumManager->deleteEntry($entry);
        return $this->redirectToRoute('forum_show_topic',['slug'=>$entry->getTopic()->getSlug()]);
    }

    /**
     * @Route("/add/new", name="forum_add_topic")
     */
    public function addTopicAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $topic = new Topic();
        $form = $this->createForm('AppBundle\Form\TopicType',$topic);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->forumManager->addTopic($topic,$this->getUser());
            return $this->redirectToRoute('forum_index');
        }

       return $this->render('forum/topic_form.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="forum_edit_topic")
     */
    public function editTopicAction(Request $request, Topic $topic)
    {
        $this->denyAccessUnlessGranted('edit', $topic);

        $form = $this->createForm('AppBundle\Form\TopicType',$topic);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->forumManager->addTopic($topic);
            return $this->redirectToRoute('forum_index');
        }
        return $this->render('forum/topic_form.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="forum_delete_topic")
     */
    public function deleteTopicAction(Topic $topic)
    {
        $this->denyAccessUnlessGranted('edit', $topic);
       $this->forumManager->deleteTopic($topic);
       return $this->redirectToRoute('forum_index');
    }



    /**
     * @Route("/profile/{username}", name="show_user_info")
     */
    public function showUserInfoAction(User $user)
    {
        return $this->render(':forum:user_info.html.twig',
            [
                'user' => $user,
            ]);
    }



}
