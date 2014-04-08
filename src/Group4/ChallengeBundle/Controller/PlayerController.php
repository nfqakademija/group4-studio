<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    }
}