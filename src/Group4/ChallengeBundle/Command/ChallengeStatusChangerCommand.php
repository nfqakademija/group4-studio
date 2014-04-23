<?php
namespace Group4\ChallengeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ChallengeStatusChangerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('challenge:status:change')
            ->setDescription('Changes challenges\'s status');
    }

    protected function execute()
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        $challengeRep = $em->getRepository('ChallengeBundle:Challenge');
        $playerToChallengeRep = $em->getRepository('ChallengeBundle:PlayerToChallenge');
        $challenges = $challengeRep->findBy(array('status' => 1));
        foreach($challenges as $challenge) {
            $playerToChallenges = $playerToChallengeRep->findBy(array('challenge' => $challenge, 'status' => 1));
            if($challenge->getVoteDate == null) {
                if(count($playerToChallenges) >= 5) {
                    //TODO: VoteDate setters / getters
                    $challenge->setVoteDate(new \DateTime("+2 hours"));
                }
            } else {
                //TODO: Patikrinti ar paskutinis speja ikelt
                $query = $em->createQuery('
                    SELECT p2c
                    FROM ChallengeBundle:PlayerToChallenge p2c
                    WHERE p2c.status = 0
                    ORDER BY p2c.date DESC
                    LIMIT 1
                ');
                $playerToChallenge = $query->getResult();
                $date = $playerToChallenge->getDate();
                $date->add(new \DateInterval(""))
                if()
            }
        }


    }
}