<?php

namespace Group4\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BaseController extends Controller
{
    public function indexAction()
    {
        return $this->render('BaseBundle:Default:index.html.twig');
    }

}
