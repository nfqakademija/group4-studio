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
     * @ORM\JoinColumn(name="p2c_id", referencedColumnName="id")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set p2cId
     *
     * @param integer $p2cId
     * @return Vote
     */
    public function setP2cId($p2cId)
    {
        $this->p2cId = $p2cId;

        return $this;
    }

    /**
     * Get p2cId
     *
     * @return integer 
     */
    public function getP2cId()
    {
        return $this->p2cId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Vote
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
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
    public function setPlayerToChallenge(\Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenge = null)
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
    public function setUser(\Group4\UserBundle\Entity\User $user = null)
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
