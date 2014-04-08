<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Group4\UserBundle\Entity\User;

/**
 * Player
 *
 * @ORM\Table(name="player_to_challenge")
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\PlayerToChallengeRepository")
 */
class PlayerToChallenge
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Group4\UserBundle\Entity\User", inversedBy="playerToChallenges")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Challenge
     *
     * @ORM\ManyToOne(targetEntity="Group4\ChallengeBundle\Entity\Challenge", inversedBy="playerToChallenges")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     */
    private $challenge;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="image_id", type="integer", nullable=true)
     */
    private $image;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param user $user
     * @return PlayerToChallenge
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get userId
     *
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set status
     *
     * @param Integer $status
     * @return PlayerToChallenge
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return PlayerToChallenge
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set challenge
     *
     * @param Challenge $challenge
     * @return PlayerToChallenge
     */
    public function setChallenge($challenge)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * Set image
     *
     * @param integer $image
     * @return PlayerToChallenge
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return integer 
     */
    public function getImage()
    {
        return $this->image;
    }
}
