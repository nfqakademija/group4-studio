<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Challenge
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\ChallengeRepository")
 */
class Challenge
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PlayerToChallenge", mappedBy="challenge")
     */
    private $playerToChallenges;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="voteDate", type="datetime", nullable=true)
     */
    private $voteDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="themeId", type="integer")
     */
    private $themeId;

    public function __construct()
    {
        $this->playerToChallenges = new ArrayCollection();
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
     * Set status
     *
     * @param integer $status
     * @return Challenge
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Challenge
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Challenge
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Challenge
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set themeId
     *
     * @param integer $themeId
     * @return Challenge
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;

        return $this;
    }

    /**
     * Get themeId
     *
     * @return integer 
     */
    public function getThemeId()
    {
        return $this->themeId;
    }

    /**
     * Add playerToChallenges
     *
     * @param \Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenges
     * @return Challenge
     */
    public function addPlayerToChallenge(\Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenges)
    {
        $this->playerToChallenges[] = $playerToChallenges;

        return $this;
    }

    /**
     * Remove playerToChallenges
     *
     * @param \Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenges
     */
    public function removePlayerToChallenge(\Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenges)
    {
        $this->playerToChallenges->removeElement($playerToChallenges);
    }

    /**
     * Get playerToChallenges
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayerToChallenges()
    {
        return $this->playerToChallenges;
    }
}
