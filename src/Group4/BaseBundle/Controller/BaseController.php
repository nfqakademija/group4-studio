<?php

namespace Group4\BaseBundle\Controller;


use Group4\ChallengeBundle\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BaseController extends Controller
{
    public function indexAction()
    {
        $typeRepository = $this->getDoctrine()
            ->getRepository('ChallengeBundle:Type');

        $types = $typeRepository->getDefaultTypes();

        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            return $this->forward('ChallengeBundle:Player:index' , array('types' => $types));
        } else {
            return $this->forward('FOSUserBundle:Security:login');
        }
    }

}
