<?php
// src/Acme/UserBundle/Entity/User.php

namespace Group4\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Group4\ChallengeBundle\Entity\Photo;
use Group4\ChallengeBundle\Entity\Vote;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Group4\ChallengeBundle\Entity\PlayerToChallenge", mappedBy="user")
     */
    private $playerToChallenges;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Group4\ChallengeBundle\Entity\Vote", mappedBy="user")
     */
    private $votes;

    /**
     * @var Photo
     *
     * @ORM\Column(name="image_id", type="integer", nullable=true)
     */
    protected $image;

    public function __construct()
    {
        parent::__construct();
    }
}