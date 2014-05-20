<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Group4\ChallengeBundle\Form\Type\ChallengeType;
use Group4\ChallengeBundle\Entity\Challenge;
use Symfony\Component\HttpFoundation\Request;


class ChallengeController extends Controller



{
    public function indexAction()
    {
        return $this->render('ChallengeBundle:Default:index.html.twig');
    }

    public function adminAction()
    {
        return $this->render('ChallengeBundle:Default:challengeAdmin.html.twig');
    }

    public function loadButtonsAction()
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Type');
        $types = $repository->getDefaultTypes();

        return $this->render('ChallengeBundle:Default:buttons.html.twig', array('types' => $types));
    }

}
