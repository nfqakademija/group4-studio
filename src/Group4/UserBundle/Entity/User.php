<?php
// src/Acme/UserBundle/Entity/User.php

namespace Group4\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Group4\ChallengeBundle\Entity\Photo;
use Group4\ChallengeBundle\Entity\Vote;
use Group4\ChallengeBundle\Entity\PlayerToChallenge;

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
     * @var ArrayCollection|\Group4\ChallengeBundle\Entity\PlayerToChallenge[]
     *
     * @ORM\OneToMany(targetEntity="\Group4\ChallengeBundle\Entity\PlayerToChallenge", mappedBy="user")
     */
    private $playerToChallenges;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Group4\ChallengeBundle\Entity\Vote", mappedBy="user")
     */
    private $votes;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Group4\ChallengeBundle\Entity\Photo", mappedBy="user")
     */
    private $photos;
    /**
     * @var Int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @var Photo
     *
     * @ORM\Column(name="image_id", type="integer", nullable=true)
     */
    protected $image;

    /**
     * @param PlayerToChallenge $playerToChallenge
     * @return $this
     */
    public function removePlayerToChallenge(PlayerToChallenge $playerToChallenge)
    {
        $this->playerToChallenges->remove($playerToChallenge);

        return $this;
    }

    /**
     * @param PlayerToChallenge $playerToChallenge
     * @return $this
     */
    public function addPlayerToChallenge(PlayerToChallenge $playerToChallenge)
    {
        $this->playerToChallenges->add($playerToChallenge);
        $playerToChallenge->setUser($this);

        return $this;
    }

    /**
     * @param Photo $image
     * @return $this
     */
    public function setImage(Photo $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Photo
     */
    public function getImage() {
        return $this->image;
    }

    public function __construct()
    {
        parent::__construct();
        $this->playerToChallenges = new ArrayCollection();
    }
}