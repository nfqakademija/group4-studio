<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="Challenge", inversedBy="playerToChallenges")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     */
    private $challenge;

    /**
     * @var ArrayCollection|Vote[]
     *
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="playerToChallenge", cascade={"all"})
     */
    private $votes;

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
     * @var Photo
     *
     * @ORM\OneToOne(targetEntity="Photo", inversedBy="playerToChallenges")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     */
    private $image;

    /**
     * @var Reward
     *
     * @ORM\OneToOne(targetEntity="Reward", inversedBy="playerToChallenge")
     * @ORM\JoinColumn(name="reward_id", referencedColumnName="id", nullable=true)
     */
    private $reward;

    /**
     * @param \Group4\ChallengeBundle\Entity\Reward $reward
     */
    public function setReward($reward)
    {
        $this->reward = $reward;
    }

    /**
     * @return \Group4\ChallengeBundle\Entity\Reward
     */
    public function getReward()
    {
        return $this->reward;
    }

    /**
     * @return int
     */
    public function getVoteCount()
    {
        return count($this->votes);
    }

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
     * @param Photo $image
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
     * @return Photo
     */
    public function getImage()
    {
        if(isset($this->image)) {
            return $this->image;
        } else {
            return null;
        }
    }

    /**
     * Set place
     *
     * @param integer $place
     * @return PlayerToChallenge
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return integer 
     */
    public function getPlace()
    {
        return $this->place;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add votes
     *
     * @param \Group4\ChallengeBundle\Entity\Vote $votes
     * @return PlayerToChallenge
     */
    public function addVote(\Group4\ChallengeBundle\Entity\Vote $vote)
    {
        foreach ($this->votes as $voteCurrent) {
            if ($voteCurrent->getUser() == $vote->getUser()) {
                return $this;
            }
        }
        $this->votes->add($vote);

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Group4\ChallengeBundle\Entity\Vote $votes
     */
    public function removeVote(\Group4\ChallengeBundle\Entity\Vote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
