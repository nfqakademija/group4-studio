<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Group4\ChallengeBundle\Form\Type\ChallengeType;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Entity\Theme;
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
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Theme');
        $themes = $repository->findBy(array('approved' => true));
        $form = $this->createForm(new ChallengeType($themes), $challenge);

        $form->handleRequest($request);
        if ($form->isValid()) {

            // Makes form id match entity id, because form id skips deleted/unapproved themes, while entity does not.
            $challenge->setThemeId($themes[$challenge->getThemeId()]->getId());

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
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Theme');
        $themes = $repository->findBy(array('approved' => true));
        //Change theme in edit form to the chosen before
        $theme = $repository->find($challenge->getThemeId());
        $themeId = 0;
                   //Getting form id from entity id because explained in newAction
        $i=0;
            foreach($themes as $t){

                if($t->getName()==$theme->getName())$themeId = $i;
                $i++;
            }
            $challenge->setThemeId($themeId);


        $form = $this->createForm(new ChallengeType($themes), $challenge);

        $form->handleRequest($request);

        if (!$challenge) {
            throw $this->createNotFoundException(
                'No challenge found for id '.$id
            );
        }

        if ($form->isValid()) {
            //Same as in newAction
            $challenge->setThemeId($themes[$challenge->getThemeId()]->getId());
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
        $repository = $this->getDoctrine()
            -> getRepository('ChallengeBundle:Theme');

        foreach($challenges as $chal){
            $theme = $repository->find($chal->getThemeId());

            if(!$theme){
                $themeName='Theme with id '.$chal->getThemeId().' deleted';
            }else{
               $themeName = $theme->getName();
            }
            $chal->setThemeId($themeName);

            // 0 - Not started yet (disabled), 1 - Started, users uploading photos already (enabled),
            // 2 - After all uploaded, voting started, //3 - Winners nominated, karma added, voting disabled.
            switch($chal->getStatus()){
                case 0:
                    $chal->setStatus("Not started");
                    break;
                case 1:
                    $chal->setStatus("Shooting/uploading");
                    break;
                case 2:
                    $chal->setStatus("Voting");
                    break;
                case 3:
                    $chal->setStatus("Ended");
                    break;
                default:
                    $chal->setStatus("Status id " + $chal->getStatus() + " not known!");

            }
            switch($chal->getType()){
                case 0:
                    $chal->setType("5 min");
                    break;
                case 1:
                    $chal->setType("15 min");
                    break;
                case 2:
                    $chal->setType("30 min");
                    break;
                case 3:
                    $chal->setType("1h");
                    break;
                case 4:
                    $chal->setType("2h");
                    break;
                case 5:
                    $chal->setType("5h");
                    break;
                case 6:
                    $chal->setType("10h");
                    break;
                case 7:
                    $chal->setType("1 day");
                    break;
                case 8:
                    $chal->setType("3 day");
                    break;
                case 9:
                    $chal->setType("5 day");
                    break;
                case 10:
                    $chal->setType("1 week");
                    break;
                case 11:
                    $chal->setType("2 week");
                    break;
                case 12:
                    $chal->setType("1 month");
                    break;
                case 13:
                    $chal->setType("3 month");
                    break;
                case 14:
                    $chal->setType("6 month");
                    break;
                case 15:
                    $chal->setType("1 year");
                    break;
                default:
                    $chal->setType("Status id " + $chal->getStatus() + " not known!");

            }

        }
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
