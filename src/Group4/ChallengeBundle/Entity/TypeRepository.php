<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;


class TypeRepository extends EntityRepository
{
    /**
     * @return ArrayCollection|Type[]
     */
    public function getDefaultTypes()
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT t FROM Group4\ChallengeBundle\Entity\Type AS t
             WHERE t.default = 1
             ORDER BY t.id ASC
         ');

        $types = $query->getResult();

        return $types;
    }

}
