<?php

namespace Group4\ChallengeBundle\Controller;


use Group4\ChallengeBundle\Entity\PlayerToChallenge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Form\Type\UploadFormType;
use Group4\ChallengeBundle\Entity\Photo;
use Group4\ChallengeBundle\Controller\ChallengeController;

const PHOTO_WIDTH = 500;
const PLAYERS_MIN = 3;
const PLAYERS_MAX = 10;
const VOTE_DATE_AFTER_DAYS = 1;
const END_DATE_AFTER_DAYS = 2;

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
                    ->where('p.status = :status AND p.type = :type AND :now BETWEEN p.startDate AND p.endDate')
                    ->setParameter('status','1')
                    ->setParameter('type', $type)
                    ->setParameter('now', new \DateTime('now'))
                    ->orderBy('p.startDate', 'ASC')
                    ->getQuery();

                $challenges = $query->getResult();



                $user = $this->container->get('security.context')->getToken()->getUser();
                $challengeController = new ChallengeController();

                $thereAreSomeFreeRooms = false;
                $repository2 = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');
                if (!empty($challenges)) {
                    foreach($challenges as $chal){
                        $now = new \DateTime("now");
                        if($now < $chal->getVoteDate() || is_null($chal->getVoteDate())){  //TODO: substract status time from now


                            if($challengeController->isNotFull($chal->getId(),PLAYERS_MAX,$repository2)){
                                $thereAreSomeFreeRooms=true;
                                if($challengeController->isAlmostMinPlayers($chal->getId(),PLAYERS_MIN,$repository2)){
                                    $voteDate = new \DateTime("now");
                                    $voteDate->modify("+".VOTE_DATE_AFTER_DAYS." days");
                                    $chal->setVoteDate($voteDate);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($chal);
                                    $em->flush();
                                }

                                $playerToChallenge = new PlayerToChallenge();
                                $playerToChallenge->setStatus(0)
                                ->setUser($user)
                                ->setDate(new \DateTime("now"))
                                ->setChallenge($chal);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($playerToChallenge);
                                $em->flush();

                                $eventId = $chal->getId();
                                break;

                            }
                        }
                    }
                }

                if(!$thereAreSomeFreeRooms){
                    $repository = $this->getDoctrine()
                        ->getRepository('ChallengeBundle:Theme');
                    $themes = $repository->findByApproved(true);
                    $challenge = new Challenge();
                    $challenge->setStatus(1);
                    $date = new \DateTime("now");
                    $date2 = new \DateTime('now + '.END_DATE_AFTER_DAYS.' days');
                    $challenge->setStartDate($date);
                    $challenge->setEndDate($date2);
                    $challenge->setThemeId($themes[rand(0,count($themes)-1)]->getId());
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

        return $this->render('ChallengeBundle:Default:JoinChallengeForm.html.twig', array('form' => $form->createView()));
    }

    public function showChallengeAction(Request $request, $eventId)
    {
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
        $event = $repository->findOneBy(
            array('id' => $eventId)
        );

        if(!is_null($event)) {
            $status = $event->getStatus();
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
                        return $this->uploadAction($request, $eventId);
                    case 1:
                        //Player uploaded the photo, waits for voting to start
                        return $this->waitForVoteAction($eventId);
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

    private function waitForVoteAction($eventId)
    {
        $userId = $this->getUser();
        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');
        $event = $repository->findOneBy(
            array('user' => $userId, 'challenge' => $eventId)
        );
        $myphoto = $event->getImage();

        $repository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
        $event = $repository->findOneBy(
            array('id' => $eventId)
        );

        $themeId = $event->getThemeId();

        return $this->render('ChallengeBundle:Player:waitForVote.html.twig', array('eventId' => $eventId, 'myphoto' => $myphoto));
    }

    private function uploadAction(Request $request, $eventId) {

        $photo = new Photo();
        $form = $this->createForm(new UploadFormType(), $photo);

        if('POST' === $request->getMethod()) {
            $form->bind($request);

            if($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $photo->setUserId($this->container->get('security.context')->getToken()->getUser()->getId());
                $em->persist($photo);
                $em->flush();

                $path = $this->get('kernel')->getRootDir() . '/../web' .'/images/challenge/'.$photo->getImageName();
                $ext = pathinfo($photo->getImageName(), PATHINFO_EXTENSION);

                if($ext == "JPG") {
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

                //TODO: Change PlayetToChallenge status to 1 after photo was uploaded
                $userId = $this->getUser();
                $em = $this->getDoctrine()->getManager();
                $event = $em->getRepository('ChallengeBundle:PlayerToChallenge');

                $eventas = $event->findOneBy(
                    array('user' => $userId, 'challenge' => $eventId)
                );

                $eventas->setStatus('1');
                $eventas->setImage($photo);
                $em->persist($eventas);
                $em->flush();

                return $this->redirect($this->generateUrl('show_challenge', array('eventId' => $eventId)));
            }

        }

        return $this->render('ChallengeBundle:Player:upload.html.twig',
            array(
                'form' => $form->createView(),
                'eventId' => $eventId
            )
        );
    }
}
