<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Group4\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class GalleryController extends Controller
{
    public function indexAction() {
        $photoRep  = $this->getDoctrine()->getRepository("ChallengeBundle:Photo");
        $photos = $photoRep->findBy(array("user" => $this->container->get("security.context")->getToken()->getUser()));

        return $this->render("UserBundle:Gallery:show.html.twig", array("photos" => $photos));
    }
}