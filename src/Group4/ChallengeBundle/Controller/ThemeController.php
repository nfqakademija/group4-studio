<?php

namespace Group4\ChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Group4\ChallengeBundle\Form\Type\ThemeType;
use Group4\ChallengeBundle\Entity\Theme;
use Symfony\Component\HttpFoundation\Request;

class ThemeController extends Controller
{
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $theme = $em->getRepository('ChallengeBundle:Theme')->find($id);

        if (!$theme) {
            throw $this->createNotFoundException(
                'No theme found for id '.$id
            );
        }

        $form = $this->createForm(new ThemeType(), $theme);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            return $this->redirect($this->generateUrl('theme_insert_success'));

        }

        return $this->render('ChallengeBundle:Default:themeEdit.html.twig', array('theme_id' => $id, 'form' => $form->createView()));
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $theme = $em->getRepository('ChallengeBundle:Theme')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($theme);
        $em->flush();

        return $this->redirect($this->generateUrl('theme_insert_success'));

    }

    public function listAllAction()
    {
        $repository = $this->getDoctrine()
            -> getRepository('ChallengeBundle:Theme');

        $themes = $repository->findAll();

        return $this->render('ChallengeBundle:Default:themeListAll.html.twig', array('themes' => $themes));
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

        return $this->render('ChallengeBundle:Default:themeNew.html.twig', array('form' => $form->createView()));
    }

    public function newSuccessAction()
    {
        return $this->render('ChallengeBundle:Default:themeNewSuccess.html.twig');
    }

    public function themeAdminAction()
    {
        return $this->render('ChallengeBundle:Default:themeAdmin.html.twig');
    }

}