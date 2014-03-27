<?php

namespace Group4\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UserBundle:index.html.twig', array('name' => $name));
    }
}