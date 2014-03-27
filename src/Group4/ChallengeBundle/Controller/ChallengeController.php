<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class ChallengeController extends Controller
{
    public function indexAction()
    {
        return $this->render('ChallengeBundle:Default:index.html.twig');
    }


}
