<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Group4\UserBundle\Entity\User;

/**
 * Vote
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\VoteRepository")
 */
class Vote
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
     * @var PlayerToChallenge
     *
     * @ORM\ManyToOne(targetEntity="PlayerToChallenge", inversedBy="votes")
     * @ORM\JoinColumn(name="player_to_challenge_id", referencedColumnName="id")
     */
    private $playerToChallenge;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Group4\UserBundle\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @param User $user
     * @param PlayerToChallenge $playerToChallenge
     * @return Vote $this
     */
    public function __construct($user, $playerToChallenge)
    {
        $this->setUser($user);
        $this->setPlayerToChallenge($playerToChallenge);
        $this->setDate(new \DateTime("now"));

        return $this;
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
     * Set date
     *
     * @param \DateTime $date
     * @return Vote
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
     * Set playerToChallenge
     *
     * @param \Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenge
     * @return Vote
     */
    public function setPlayerToChallenge(\Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenge)
    {
        $this->playerToChallenge = $playerToChallenge;

        return $this;
    }

    /**
     * Get playerToChallenge
     *
     * @return \Group4\ChallengeBundle\Entity\PlayerToChallenge 
     */
    public function getPlayerToChallenge()
    {
        return $this->playerToChallenge;
    }

    /**
     * Set user
     *
     * @param \Group4\UserBundle\Entity\User $user
     * @return Vote
     */
    public function setUser(\Group4\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Group4\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
