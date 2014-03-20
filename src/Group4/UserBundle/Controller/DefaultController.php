<?php

namespace Group4\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UserBundle:index.html.twig', array('name' => $name));
    }
}
