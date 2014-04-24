<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

const PLAYERS_MAX = 10;
/**
 * ChallengeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChallengeRepository extends EntityRepository
{
    /**
     * @param int $type
     * @param boolean $full
     * @return ArrayCollection|Challenge[]
     */
    public function getActiveChallenges($type = 1, $full = false)
    {
        $now = date("Y-m-d-H:i:s");
        $query = $this->getEntityManager()->createQuery('
            SELECT c FROM Group4\ChallengeBundle\Entity\Challenge AS c
            JOIN c.playerToChallenges AS p2c
             WHERE c.status = 1 AND c.type ='.$type.' AND :now BETWEEN c.startDate AND c.voteDate
            ORDER BY c.startDate DESC
         ')->setParameter('now',new \DateTime('now'));

        $challenges = $query->getResult();

        if (!$full) {
             foreach($challenges as $key => $challenge) {
                if ($challenge->getPlayersCount() >= PLAYERS_MAX) {
                    unset($challenges[$key]);
                }
             }
        }

        return $challenges;
    }

    /**
     * @param int $type
     * @param bool $full
     * @return Challenge
     */
    public function getActiveChallenge($type = 1, $full = false)
    {
        $now = date("Y-m-d-H:i:s");

        $query = $this->getEntityManager()->createQuery('
            SELECT c FROM Group4\ChallengeBundle\Entity\Challenge AS c
            JOIN c.playerToChallenges AS p2c
            WHERE c.status = 1 AND c.type ='.$type.' AND :now BETWEEN c.startDate AND c.endDate
            ORDER BY c.startDate DESC
         ')->setParameter('now',new \DateTime('now'));

        if(!$full) {
            $challenges = $query->getResult();
        } else {
            return $query->getOneOrNullResult();
        }

        foreach($challenges as $challenge) {
            if($challenge->getPlayersCount()<PLAYERS_MAX) {
                return $challenge;
            }
        }

        return null;
    }
}
