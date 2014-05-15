<?php

namespace Group4\ChallengeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Group4\ChallengeBundle\Form\Type\ChallengeType;
use Group4\ChallengeBundle\Entity\Challenge;
use Symfony\Component\HttpFoundation\Request;


class StatsController extends Controller



{
    public function indexAction()
    {
        $challengeRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Challenge');
        $photoRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Photo');
        $playerToChallengeRepository = $this->getDoctrine()->getRepository('ChallengeBundle:PlayerToChallenge');
        $rewardRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Reward');
        $themeRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Theme');
        $typeRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Type');
        $voteRepository = $this->getDoctrine()->getRepository('ChallengeBundle:Vote');
        $userRepository = $this->getDoctrine()->getRepository('UserBundle:User');

        $challenges = $challengeRepository->findAll();
        $playerToChallenges = $playerToChallengeRepository->findAll();
        $themes = $themeRepository->findAll();
        $votes = $voteRepository->findAll();
        $users = $userRepository->findAll();
        $stat = array();
        $stat[0] = count($challenges);
        for($i=0;$i<13;$i++){
            $stat[$i]=0;
        }
        foreach($challenges as $chal){
            switch($chal->getStatus()){
                case 0:
                    $stat[1]++;
                    break;
                case 1;
                    $stat[2]++;
                    break;
                case 3;
                    $stat[3]++;
                    break;
                case 4;
                    $stat[5]++;
                    break;
            }

        }

        $stat[6]=count($playerToChallenges);
        foreach($playerToChallenges as $ptc){
            switch($ptc->getStatus()){
                case 0:
                    $stat[7]++;
                    break;
                case 1:
                    $stat[8]++;
                    break;
            }

        }

        $stat[6]=count($playerToChallenges);
        foreach($playerToChallenges as $ptc){
            switch($ptc->getStatus()){
                case 0:
                    $stat[7]++;
                    break;
                case 1:
                    $stat[8]++;
                    break;
            }

        }

        $stat[9]=count($themes);
        foreach($themes as $theme){
            switch($theme->getApproved()){
                case 0:
                    $stat[10]++;
                    break;
                case 1:
                    $stat[11]++;
                    break;
            }

        }

        $stat[12]=count($votes);
        $stat[13]=count($users);



        return $this->render('ChallengeBundle:Stats:index.html.twig', array("stat" => $stat));
    }
}