<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Group4\ChallengeBundle\Form\Type\ChallengeType;
use Group4\ChallengeBundle\Entity\Challenge;
use Symfony\Component\HttpFoundation\Request;


class ChallengeController extends Controller



{
    public function indexAction()
    {
        return $this->render('ChallengeBundle:Default:index.html.twig');
    }

    public function newAction(Request $request)
    {
        $challenge = new Challenge();

        $form = $this->createForm(new ChallengeType(), $challenge);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($challenge);
            $em->flush();

            return $this->redirect($this->generateUrl('challenge_insert_success'));

        }

        return $this->render('ChallengeBundle:Default:challengeNew.html.twig', array('form' => $form->createView()));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $challenge = $em->getRepository('ChallengeBundle:Challenge')->find($id);

        if (!$challenge) {
            throw $this->createNotFoundException(
                'No challenge found for id '.$id
            );
        }

        $form = $this->createForm(new ChallengeType(), $challenge);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($challenge);
            $em->flush();

            return $this->redirect($this->generateUrl('challenge_insert_success'));

        }

        return $this->render('ChallengeBundle:Default:challengeEdit.html.twig', array('challenge_id' => $id, 'form' => $form->createView()));
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $challenge = $em->getRepository('ChallengeBundle:Challenge')->find($id);

        $em->remove($challenge);
        $em->flush();

        return $this->redirect($this->generateUrl('challenge_insert_success'));

    }

    public function listAllAction()
    {
        $repository = $this->getDoctrine()
            -> getRepository('ChallengeBundle:Challenge');

        $challenges = $repository->findAll();

        return $this->render('ChallengeBundle:Default:challengeListAll.html.twig', array('challenges' => $challenges));
    }

    public function newSuccessAction(){
        return $this->render('ChallengeBundle:Default:challengeNewSuccess.html.twig');
    }

    public function adminAction()
    {
        return $this->render('ChallengeBundle:Default:challengeAdmin.html.twig');
    }



}
