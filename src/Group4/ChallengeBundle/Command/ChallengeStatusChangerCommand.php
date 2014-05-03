<?php
namespace Group4\ChallengeBundle\Command;

use Group4\ChallengeBundle\Entity\Reward;
use Group4\ChallengeBundle\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ChallengeStatusChangerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('challenge:status:change')
            ->setDescription('Changes challenges\'s status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        $challengeRep = $em->getRepository('ChallengeBundle:Challenge');
        $playerToChallengeRep = $em->getRepository('ChallengeBundle:PlayerToChallenge');
        $challenges = $challengeRep->findBy(array('status' => 1));

        foreach($challenges as $challenge) {
            $playerToChallenges = $playerToChallengeRep->findBy(array('challenge' => $challenge, 'status' => 1));
            if($challenge->getVoteDate() == null) {
                if(count($playerToChallenges) >= 5) {
                    $challenge->setVoteDate(new \DateTime("+2 hours"));
                }
            }
            $voteDate = new \DateTime("now");
            $voteDate->add(new \DateInterval("PT1H"));
            if($challenge->getVoteDate() >= $voteDate ) {
                $query = $em->createQuery('
                    SELECT p2c
                    FROM ChallengeBundle:PlayerToChallenge p2c
                    WHERE p2c.status = 0
                    ORDER BY p2c.date DESC
                ');
                $playerToChallenges = $query->getResult();
                if (empty($playerToChallenges)) {
                    $challenge->setStatus(2);
                    $em->persist($challenge);
                } else {
                    $date = $playerToChallenges[0]->getDate();
                    $date->add(new \DateInterval("PT15M"));
                    $challengeVoteDate = $challenge->getVoteDate();
                    $challengeVoteDate->add(new \DateInterval("PT1H"));
                    if($date > $challengeVoteDate) {
                        $newDate = new \DateTime("now");
                        $newDate->add(new \DateInterval("PT15M"));
                        $challenge->setVoteDate($newDate);
                        $em->persist($challenge);
                    }
                }
                $em->flush();
            }
        }

            //TODO: Patikrinti ar challenge laikas pasibaiges, jei taip challenge.status = 3, isdalinti rewards
            //TODO: Nepamarsti apie atveji, kai nesusirenka penki zaidejai
            //TODO: Patikrinti ir su challenge.status = 2
        $challenges = $challengeRep->findBy(array('status' => 1, 'status' => 2));

        foreach($challenges as $challenge) {
            $playerToChallenges = $challenge->getPlayerToChallenges();
            if ($challenge->getEndDate() <= new \DateTime("now")) {
                $challenge->setStatus(3);
                $em->persist($challenge);

                $iterator = $playerToChallenges->getIterator();
                $iterator->uasort(function ($a, $b) {
                    return ($a->getVoteCount() > $b->getVoteCount()) ? -1 : 1;
                });

                $playerToChallenges = new ArrayCollection(iterator_to_array($iterator));

                if($challenge->getPlayersCount() >= 5) {
                    //TODO: Isdalinti taskus pagal vietas
                    $bonus = 10*$challenge->getPlayersCount();
                    $win = 3;
                    foreach($playerToChallenges as $playerToChallenge) {
                        $points = $playerToChallenge->getVoteCount() + $bonus;
                        $bonus = $bonus * 0.5;
                        $win -= 1;

                        if($win == 0) {
                            $bonus = 0;
                        }

                        $reward = new Reward($playerToChallenge, $points);
                        $em->persist($reward);

                        $playerToChallenge->setReward($reward);
                        $em->persist($playerToChallenge);
                    }

                } else {
                    //TODO: Isdalinti dalyvavusiems po 10 tasku
                    foreach($playerToChallenges as $playerToChallenge) {
                        $reward = new Reward($playerToChallenge, 10);
                        $em->persist($reward);

                        $playerToChallenge->setReward($reward);
                        $em->persist($playerToChallenge);
                    }
                }
            $em->flush();
            }

        }
    }
}