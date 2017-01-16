<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Tests\Extension\Core\Type\SubmitTypeTest;
use Symfony\Component\HttpFoundation\File\UploadedFile as File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Uploadedfile controller.
 *
 * @Route("/downloads")
 */
class DownloadsController extends Controller
{

    /**
     * @Route("/get/{uri}", name="downloads_get")
     */
    public function downloadAction($uri)
    {
        if(!$this->isGranted("IS_AUTHENTICATED_FULLY")) return $this->redirectToRoute('downloads_index');
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:UploadedFile');
        $file = $repo->findOneBy(['uri'=>$uri]);


        if(!$file || !file_exists($file->getAbsolutePath())) throw $this->createNotFoundException('File is not available!') ;
        $response = new Response($file->getAbsolutePath());

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getFileName().'.'.$file->getExtension()
        );
        $response->headers->set('Content-Disposition', $disposition);
        $file->setViews(1);
        $this->getDoctrine()->getManager()->flush();
        return $response;

    }


    /**
     * Lists all uploadedFile entities.
     *
     * @Route("/", name="downloads_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $uploadedFiles = $em->getRepository('AppBundle:UploadedFile')->findAll();

        return $this->render('uploadedfile/index.html.twig', array(
            'uploadedFiles' => $uploadedFiles,
        ));
    }

    /**
     * Creates a new uploadedFile entity.
     *
     * @Route("/new", name="downloads_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',null,"You do NOT possess Admin priviliges.");
        $uploadedFile = new Uploadedfile();
        $form = $this->createForm('AppBundle\Form\UploadedFileType', $uploadedFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $file File
             */
            $file = $uploadedFile->getContent();
            $extension = $file->guessExtension();
            $client_filename = str_replace(' ', '-',pathinfo($file->getClientOriginalName())['filename']);

            $uploadedFile->setExtension($extension);
            // if filename is not given, use the client_filename
            if(!$uploadedFile->getFileName())
                $uploadedFile->setFileName($client_filename);


            $uploadedFile->setSize($file->getSize());
            $db_filename = md5(uniqid()).$uploadedFile->getFileName();
            $db_abs_path = $this->getParameter('files_dir').$uploadedFile->getFileName().'.'.$extension;
            $uploadedFile->setAbsolutePath($db_abs_path);
            $fileinfo = pathinfo($uploadedFile->getAbsolutePath());
            $file->move($fileinfo['dirname'],$fileinfo['basename']);
            $uploadedFile->setUri($fileinfo['basename']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($uploadedFile);
            $em->flush($uploadedFile);

            return $this->redirectToRoute('downloads_show', array('id' => $uploadedFile->getId()));
        }

        return $this->render('uploadedfile/new.html.twig', array(
            'uploadedFile' => $uploadedFile,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a uploadedFile entity.
     *
     * @Route("/{id}", name="downloads_show")
     * @Method("GET")
     */
    public function showAction(UploadedFile $uploadedFile)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',null,"You do NOT possess Admin priviliges.");
        return $this->render('uploadedfile/show.html.twig', array(
            'uploadedFile' => $uploadedFile,
        ));
    }

    /**
     * Displays a form to edit an existing uploadedFile entity.
     *
     * @Route("/{id}/edit", name="downloads_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UploadedFile $uploadedFile)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',null,"You do NOT possess Admin priviliges.");
        $editForm = $this->createFormBuilder($uploadedFile)
        ->add('filename')->add('comment')->getForm();
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('downloads_show', array('id' => $uploadedFile->getId()));
        }

        return $this->render('uploadedfile/edit.html.twig', array(
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a uploadedFile entity.
     *
     * @Route("/delete/{id}", name="downloads_delete")
     *
     */
    public function deleteAction(Request $request, UploadedFile $uploadedFile)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',null,"You do NOT possess Admin priviliges.");
        $em = $this->getDoctrine()->getManager();
        $em->remove($uploadedFile);
        $em->flush($uploadedFile);
        return $this->redirectToRoute('downloads_index');
    }

}
