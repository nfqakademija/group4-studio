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
use Group4\ChallengeBundle\Entity\Type;

const PHOTO_WIDTH = 500;

class PlayerController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        $challengeRepository = $this->getDoctrine()
            ->getRepository('ChallengeBundle:Challenge');
        $allRecentChallenges = $challengeRepository->findBy(array('status' => 2));
        $allOngoingChallenges = $challengeRepository->findBy(array('status' => 1));
        $myRecentChallenges = array();
        $myOngoingChallenges = array();
        foreach($allOngoingChallenges as $chal){
            if($chal->isInChallenge($user)){
                foreach($chal->getPlayerToChallenges() as $ptc){
                    if($ptc->getUser() == $user){
                        if($ptc->getStatus()==0){
                            $time = $ptc->getDate();
                            $time->add($chal->getType()->getUploadDurationInterval());
                            if($time >= new \DateTime("now")) {
                                $timeLeft = $time->diff(new \DateTime("now"));
                                //TODO: show timeleft
                                $myOngoingChallenges[]=$chal;
                            }
                        }
                    }
                }
            }
        }

        foreach($allRecentChallenges as $chal){
            if($chal->isInChallenge($user)){
                foreach($chal->getPlayerToChallenges() as $ptc){
                    if($ptc->getUser() == $user){
                        if($ptc->getStatus()==1){
                            $myRecentChallenges[]=$chal;
                        }
                    }
                }
            }
        }
        return $this->render('ChallengeBundle:Default:index.html.twig', array('allRecentChallenges' => $allRecentChallenges, 'myOngoingChallenges' => $myOngoingChallenges, 'myRecentChallenges' => $myRecentChallenges));
    }

    public function joinChallengeAction($type = 0)
    {
        $challengeRepository = $this->getDoctrine()
            ->getRepository('ChallengeBundle:Challenge');

        $challenge = $challengeRepository->getActiveChallenge($type);
        $themeRepository = $this->getDoctrine()
            ->getRepository('ChallengeBundle:Theme');

        $typeRepository = $this->getDoctrine()
            ->getRepository('ChallengeBundle:Type');

        $user = $this->getUser();

        if (!empty($challenge) && !$challenge->isInChallenge($user) ) {
            $challenge->join($user);
        } else {
            $themes = $themeRepository->getApprovedThemes();
            $typeObj = $typeRepository->find($type);
            $challenge = new Challenge($themes[rand(0,count($themes)-1)],$typeObj);
            $challenge->join($user);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($challenge);
        $em->flush();

        $eventId = $challenge->getId();

        return $this->redirect($this->generateUrl('show_challenge', array('eventId' => $eventId)));
    }

    public function showChallengeAction(Request $request, $eventId, $userId=null)
    {

        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
        $event = $repository->findOneBy(
            array('id' => $eventId)
        );

        if(!is_null($event)) {
            $status = $event->getStatus();
            $theme = $event->getTheme();
            $type = $event->getType();
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
                $userId = $this->container->get('security.context')->getToken()->getUser();
                return $this->votingAction($event, $userId);
            case 3:
            //Challenge ended
            //TODO: redirect to challengeEndedStateAction
                return $this->challengeOverviewAction($event, $userId);
            default:
            //Error handler
                return $this->render('BaseBundle:Default:404.html.twig');
        }


    }

    private function votingAction($event, $user)
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:playerToChallenge');
        $playerToChallenges = $repository->findBy(array('challenge' => $event));
        return $this->render('ChallengeBundle:Player:voting.html.twig', array('players' => $playerToChallenges, 'challenge' => $event, 'user' => null, 'usr' => $user) );
    }

    private function challengeOverviewAction($event, $user)
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:playerToChallenge');
        $playerToChallenges = $repository->findBy(array('challenge' => $event));
        return $this->render('ChallengeBundle:Player:challengeOverview.html.twig', array('players' => $playerToChallenges, 'challenge' => $event, 'user' => $user) );
    }

    public function voteAction($playerToChallengeId)
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');
        $playerToChallenge = $repository->findOneBy(array('id' => $playerToChallengeId));
        $voteRep = $this->getDoctrine()->getRepository('ChallengeBundle:Vote');
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('
            SELECT v
            FROM Group4\ChallengeBundle\Entity\Vote v
            LEFT JOIN v.playerToChallenge p2c
            LEFT JOIN p2c.challenge c
            WHERE v.user = :user
                AND c = :challenge
        ')
            ->setParameter('user', $this->container->get('security.context')->getToken()->getUser())
            ->setParameter('challenge', $playerToChallenge->getChallenge());
        $vote = $query->getResult();
        if (is_array($vote)) {
            foreach ($vote as $voteTmp) {
                $em->remove($voteTmp);
            }
        } else {
            $em->remove($vote);
        }
        $vote = new Vote($this->container->get('security.context')->getToken()->getUser(),$playerToChallenge);
        $playerToChallenge->addVote($vote);

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
        $players = $event->getPlayersUploadedCount();

        $voteDate = $event->getVoteDate();
        if(!is_null($voteDate)){
            $timeLeft = $voteDate->diff(new \DateTime("now"));
        }else{
            $timeLeft = new \DateInterval("P10Y");
        }
        //Info about challenge END

        return $this->render('ChallengeBundle:Player:waitForVote.html.twig', array('eventId' => $eventId, 'myphoto' => $myphoto,
            'theme' => $theme, 'players' => $players, 'timeLeft' => $timeLeft->y*31556926 + $timeLeft->m*2629743 + $timeLeft->d*86400 + $timeLeft->h*3600 + $timeLeft->i*60 + $timeLeft->s));
    }

    private function uploadAction(Request $request, $eventId, $theme) {
        //TODO: get and show time left till playerToChallenge.date+challenge.type
        $photo = new Photo();
        $form = $this->createForm(new UploadFormType(), $photo);

        $playerToChallengeRep = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');
        $playerToChallenge = $playerToChallengeRep->findOneBy(array('user' => $this->container->get('security.context')->getToken()->getUser(), 'challenge' => $eventId));
        $time = $playerToChallenge->getDate();

        $time->add($playerToChallenge->getChallenge()->getType()->getUploadDurationInterval());


        $em = $this->getDoctrine()->getManager();

        if($time <= new \DateTime("now")) {
            $em->remove($playerToChallenge);
            $em->flush();
            return $this->forward('ChallengeBundle:Player:showChallenge', array('request' => $request, 'eventId' => $eventId));
        } else {
            $timeLeft = $time->diff(new \DateTime("now"));
        }


        if('POST' === $request->getMethod()) {
            $form->bind($request);

            if($form->isValid()) {

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
                $challenge = $em->getRepository('ChallengeBundle:Challenge');

                $pl2ch = $event->findOneBy(
                    array('user' => $user, 'challenge' => $eventId)
                );
                $ch = $challenge->findOneBy(array('id' => $eventId));

                $pl2ch->setStatus('1');
                $pl2ch->setImage($photo);

                $em->persist($pl2ch);
                $em->flush();
                $ch->doVoteDateStuff();
                return $this->redirect($this->generateUrl('show_challenge', array('eventId' => $eventId)));
            }

        }

        return $this->render('ChallengeBundle:Player:upload.html.twig',
            array(
                'form' => $form->createView(),
                'eventId' => $eventId,
                'theme' => $theme,
                'timeLeft' => $timeLeft->y*31556926 + $timeLeft->m*2629743 + $timeLeft->d*86400 + $timeLeft->h*3600 + $timeLeft->i*60 + $timeLeft->s
            )
        );
    }
}
