<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
<<<<<<< HEAD
use Symfony\Component\HttpFoundation\Request;
use Group4\ChallengeBundle\Entity\Challenge;

class PlayerController extends Controller
{
    public function JoinChallengeFormAction(Request $request)
    {

        $form = $this->createFormBuilder()
            ->add('Type', 'choice', array(
                'choices'   => array('1' => '5 minutes', '2' => '15 minutes'),
                'required'  => true,
            ))
            ->add('Start Challenge', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $type = $form->get('Type')->getData();
            $cRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
            $challenges = $cRepository->getByStatus(1);
            $tRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Theme');

            if (!empty($challenges)) {
                //TODO: function to join da challenge
            } else {
                $themes = $tRepository->listAll();
                $challenge = new Challenge();
                $challenge->setStatus(1);
                $challenge->setStartDate(date("Y-m-d H:i:s"));
                $challenge->setEndDate(date("Y-m-d H:i:s", strtotime("+2 days")));
                $challenge->setThemeId(rand(1,count($themes)));

                $em = $this->getDoctrine()->getManager();
                $em->persist($challenge);
                $em->flush();


            }
        }

        return $this->render('ChallengeBundle:Default:JoinChallengeForm.html.twig', array('form' => $form->createView()));
=======
use Group4\ChallengeBundle\Form\Type\UploadFormType;
use Group4\ChallengeBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\Request;


class PlayerController extends Controller
{
    public function uploadAction(Request $request) {

        $photo = new Photo();
        $form = $this->createForm(new UploadFormType(), $photo);

        if('POST' === $request->getMethod()) {
            $form->bind($request);

            if($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $photo->setUserId($this->getUser()->getId());
                $em->persist($photo);
                $em->flush();

                return $this->render('ChallengeBundle:Player:successUpload.html.twig', array('photo'=>$photo));
            }

        }

        return $this->render('ChallengeBundle:Player:upload.html.twig',
            array(
                'form' => $form->createView()
            )
        );
>>>>>>> origin/master

    }
}