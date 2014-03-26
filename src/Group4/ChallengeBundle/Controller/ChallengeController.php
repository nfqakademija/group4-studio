<?php

namespace Group4\ChallengeBundle\Controller;

use Group4\ChallengeBundle\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Group4\ChallengeBundle\Form\Type\ThemeType;

class ChallengeController extends Controller
{
    public function indexAction()
    {
        return $this->render('ChallengeBundle:Default:index.html.twig');
    }

    public function listAllAction()
    {
        $repository = $this->getDoctrine()
        -> getRepository('ChallengeBundle:Theme');

        $themes = $repository->findAll();

        return $this->render('ChallengeBundle:Default:listAll.html.twig', array('themes' => $themes));
    }

    public function newAction(Request $request)
    {
        $theme = new Theme();

        $form = $this->createForm(new ThemeType(), $theme);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            return $this->redirect($this->generateUrl('theme_insert_success'));

        }

        return $this->render('ChallengeBundle:Default:new.html.twig', array('form' => $form->createView()));
    }

    public function newSuccessAction()
    {
        return $this->render('ChallengeBundle:Default:newSuccess.html.twig');
    }
}
