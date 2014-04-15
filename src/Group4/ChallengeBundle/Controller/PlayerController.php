<?php

namespace Group4\ChallengeBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Group4\ChallengeBundle\Entity\PlayerToChallenge;
use Group4\ChallengeBundle\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Form\Type\UploadFormType;
use Group4\ChallengeBundle\Entity\Photo;

const PHOTO_WIDTH = 500;

class PlayerController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
        $challenges = $repository->findBy(array('status' => 2));
        return $this->render('ChallengeBundle:Default:index.html.twig', array('challenges' => $challenges));
    }

    public function joinChallengeAction($type)
    {
        $repository = $this->getDoctrine()
            ->getRepository('ChallengeBundle:Challenge');

        $challenge = $repository->getActiveChallenge($type);
        $repository = $this->getDoctrine()
            ->getRepository('ChallengeBundle:Theme');

        $user = $this->getUser();

        if (!empty($challenge) && !$challenge->isInChallenge($user)) {
            $playerToChallenge = new PlayerToChallenge();
            $playerToChallenge->setStatus(0)
                ->setUser($user)
                ->setDate(new \DateTime("now"));
            $challenge->addPlayerToChallenge($playerToChallenge);
        } else {
            $themes = array();
            $themes = $repository->findByApproved(true);
            $challenge = new Challenge($themes[rand(0,count($themes)-1)],$type);
            $playerToChallenge = new PlayerToChallenge();
            $playerToChallenge->setStatus(0)
                ->setUser($user)
                ->setDate(new \DateTime("now"));
            $challenge->addPlayerToChallenge($playerToChallenge);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($challenge);
        $em->flush();

        $eventId = $challenge->getId();

        return $this->redirect($this->generateUrl('show_challenge', array('eventId' => $eventId)));
    }

    public function showChallengeAction(Request $request, $eventId)
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
        $event = $repository->findOneBy(
            array('id' => $eventId)
        );

        if(!is_null($event)) {
            $status = $event->getStatus();
            $theme = $event->getTheme();
        } else {
            return $this->render('BaseBundle:Default:404.html.twig');
        }

        switch($status) //Switch by Challenge.status
        {
            case 0:
            //Disabled, not started
                return $this->render('BaseBundle:Default:404.html.twig');
            case 1:
            //Enabled, waiting for players
                $user = $this->container->get('security.context')->getToken()->getUser();
                $repository = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');

                $event = $repository->findOneBy(
                    array('user' => $user, 'challenge' => $eventId)
                );
                if(!is_null($event)) {
                    $status = $event->getStatus();
                } else {
                    //TODO: Redirect to check the challenge for non-participants (show JOIN CHALLENGE button)
                    return $this->render('BaseBundle:Default:404.html.twig');
                }

                switch ($status) //Switch by playerToChallenge.status
                {
                    case 0:
                        //Player has not yet uploaded the photo
                        return $this->uploadAction($request, $eventId, $theme);
                    case 1:
                        //Player uploaded the photo, waits for voting to start
                        return $this->waitForVoteAction($eventId, $theme);
                    default:
                        return $this->render('BaseBundle:Default:404.html.twig');
                }

            case 2:
            //Voting state
                return $this->votingAction($event);
            case 3:
            //Challenge ended
            //TODO: redirect to challengeEndedStateAction
            default:
            //Error handler
                return $this->render('BaseBundle:Default:404.html.twig');
        }


    }

    private function votingAction($event)
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:playerToChallenge');
        $playerToChallenges = array();
        $playerToChallenges = $repository->findBy(array('challenge' => $event));
        return $this->render('ChallengeBundle:Player:voting.html.twig', array('players' => $playerToChallenges, 'challenge' => $event));
    }

    public function voteAction($playerToChallengeId)
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');
        $playerToChallenge = $repository->findOneBy(array('id' => $playerToChallengeId));
        $vote = new Vote($this->container->get('security.context')->getToken()->getUser(),$playerToChallenge);
        $playerToChallenge->addVote($vote);

        $em = $this->getDoctrine()->getManager();
        $em->persist($playerToChallenge);
        $em->flush();

        return $this->redirect($this->generateUrl('show_challenge', array('eventId' => $playerToChallenge->getChallenge()->getId())));
    }

    private function waitForVoteAction($eventId, $theme)
    {
        //Info about player BEGIN
        $userId = $this->getUser();
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');
        $event = $repository->findOneBy(
            array('user' => $userId, 'challenge' => $eventId)
        );
        $myphoto = $event->getImage();
        //Info about player END

        //Info about challenge BEGIN
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
        $event = $repository->findOneBy(
            array('id' => $eventId)
        );
        //TODO: get voteDate (if null, then waiting for players)
        //Info about challenge END

        return $this->render('ChallengeBundle:Player:waitForVote.html.twig', array('eventId' => $eventId, 'myphoto' => $myphoto, 'theme' => $theme));
    }

    private function uploadAction(Request $request, $eventId, $theme) {
        //TODO: get and show time left till playerToChallenge.date+challenge.type
        $photo = new Photo();
        $form = $this->createForm(new UploadFormType(), $photo);

        if('POST' === $request->getMethod()) {
            $form->bind($request);

            if($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $photo->setUser($this->container->get('security.context')->getToken()->getUser());
                $em->persist($photo);
                $em->flush();

                $path = $this->get('kernel')->getRootDir() . '/../web' .'/images/challenge/'.$photo->getImageName();
                $ext = pathinfo($photo->getImageName(), PATHINFO_EXTENSION);

                if(($ext == "JPG")||($ext == "jpg")) {
                    $image = imagecreatefromjpeg($path);
                } else {
                    $image = imagecreatefrompng($path);
                }

                list($width, $height) = getimagesize($path);

                if($width>$height) {
                    $resizeRatio = PHOTO_WIDTH/$width;
                    $newWidth = $width * $resizeRatio;
                    $newHeight = $height * $resizeRatio;
                } else {
                    $resizeRatio = PHOTO_WIDTH/$height;
                    $newWidth = $width * $resizeRatio;
                    $newHeight = $height * $resizeRatio;
                }

                $new = imagecreatetruecolor($newWidth,$newHeight);
                imagecopyresampled($new,$image,0,0,0,0,$newWidth,$newHeight,$width,$height);

                if($ext == "JPG") {
                    imagejpeg($new, $path, 75);
                } else {
                    imagepng($new, $path, 9);
                }

                $user = $this->container->get('security.context')->getToken()->getUser();
                $em = $this->getDoctrine()->getManager();
                $event = $em->getRepository('ChallengeBundle:PlayerToChallenge');

                $pl2ch = $event->findOneBy(
                    array('user' => $user, 'challenge' => $eventId)
                );

                $pl2ch->setStatus('1');
                $pl2ch->setImage($photo);
                $em->persist($pl2ch);
                $em->flush();

                return $this->redirect($this->generateUrl('show_challenge', array('eventId' => $eventId)));
            }

        }

        return $this->render('ChallengeBundle:Player:upload.html.twig',
            array(
                'form' => $form->createView(),
                'eventId' => $eventId,
                'theme' => $theme
            )
        );
    }
}
