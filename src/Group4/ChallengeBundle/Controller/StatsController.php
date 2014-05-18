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
        $stat = array();
        $stat[0] = count($challengeRepository->findAll());
        $stat[1] = count($challengeRepository->findBy(array("status" => 0)));
        $stat[2] = count($challengeRepository->findBy(array("status" => 1)));
        $stat[3] = count($challengeRepository->findBy(array("status" => 2)));
        $stat[4] = count($challengeRepository->findBy(array("status" => 3)));
        $stat[5] = count($playerToChallengeRepository->findAll());
        $stat[6] = count($playerToChallengeRepository->findBy(array("status" => 0)));
        $stat[7] = count($playerToChallengeRepository->findBy(array("status" => 1)));
        $stat[8] = count($themeRepository->findAll());
        $stat[9] = count($themeRepository->findBy(array("approved" => 0)));
        $stat[10] = count($themeRepository->findBy(array("approved" => 1)));
        $stat[11] = count($voteRepository->findAll());
        $stat[12] = count($userRepository->findAll());
        $stat[13] = count($photoRepository->findAll());

        $users = $userRepository->findBy(array("enabled" => true),array("score" => 'DESC'));
        $users = array_slice($users, 0, 10);




        return $this->render('ChallengeBundle:Stats:index.html.twig', array("stat" => $stat, "users" => $users));
    }
}