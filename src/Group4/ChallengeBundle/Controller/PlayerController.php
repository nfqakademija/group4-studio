<?php

namespace Group4\ChallengeBundle\Controller;


use Group4\ChallengeBundle\Entity\PlayerToChallenge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Form\Type\UploadFormType;
use Group4\ChallengeBundle\Entity\Photo;

const PHOTO_WIDTH = 500;

class PlayerController extends Controller
{
    public function JoinChallengeFormAction(Request $request)
    {
        //TODO: Perkelti i entity
        $form = $this->createFormBuilder()
            ->add('Type', 'choice', array(
                'choices'   => array('1' => '5 minutes', '2' => '15 minutes'),
                'required'  => true,
            ))
            ->add('Start Challenge', 'submit')
            ->setAction($this->generateUrl("challenge_me"))
            ->getForm();

        $form->handleRequest($request);

        if ('POST' === $request->getMethod()) {
            if ($form->isValid()) {
                $type = $form->get('Type')->getData();

                $repository = $this->getDoctrine()
                    ->getRepository('ChallengeBundle:Challenge');

                $query = $repository->createQueryBuilder('p')
                    ->where('p.status = :status AND p.type = :type')
                    ->setParameter('status','1')
                    ->setParameter('type', $type)
                    ->orderBy('p.startDate', 'DESC')
                    ->getQuery();

                $challenges = array();
                $challenges = $query->getResult();

                $repository = $this->getDoctrine()
                    ->getRepository('ChallengeBundle:Theme');

                $user = $this->container->get('security.context')->getToken()->getUser();

                if (!empty($challenges)) {
                    $playerToChallenge = new PlayerToChallenge();
                    $playerToChallenge->setStatus(0)
                        ->setUser($user)
                        ->setDate(new \DateTime("now"))
                        ->setChallenge($challenges[0]);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($playerToChallenge);
                    $em->flush();

                    $eventId = $challenges[0]->getId();

                } else {
                    $themes = array();
                    $themes = $repository->findByApproved(true);
                    $challenge = new Challenge();
                    $challenge->setStatus(1);
                    $date = new \DateTime("now");
                    $challenge->setStartDate($date);
                    $challenge->setEndDate($date->modify("+2 days"));
                    $challenge->setTheme($themes[rand(0,count($themes)-1)]);
                    $challenge->setType($type);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($challenge);
                    $em->flush();

                    $eventId = $challenge->getId();

                    $playerToChallenge = new PlayerToChallenge();
                    $playerToChallenge->setStatus(0)
                        ->setUser($user)
                        ->setDate(new \DateTime("now"))
                        ->setChallenge($challenge);

                    $em->persist($playerToChallenge);
                    $em->flush();
                }

                return $this->redirect($this->generateUrl('show_challenge', array('eventId' => $eventId)));
            }
        }

        return $this->render('ChallengeBundle:Default:joinChallengeForm.html.twig', array('form' => $form->createView()));
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

//                $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Theme');
//                $event = $repository->findOneBy(
//                    array('id' => $themeId)
//                );
//                $theme = $event->getName();

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
            //TODO: redirect to votingStateAction
            case 3:
            //Challenge ended
            //TODO: redirect to challengeEndedStateAction
            default:
            //Error handler
                return $this->render('BaseBundle:Default:404.html.twig');
        }


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
