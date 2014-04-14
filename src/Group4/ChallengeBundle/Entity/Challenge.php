<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Group4\UserBundle\Entity\User;
use Group4\ChallengeBundle\Entity\PlayerToChallenge;

/**
 * Challenge
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\ChallengeRepository")
 */
class Challenge
{
    /**
     * @var ArrayCollection|PlayerToChallenge[]
     *
     * @ORM\OneToMany(targetEntity="PlayerToChallenge", mappedBy="challenge", cascade={"all"})
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

    /**
     * @param int $themeId
     * @param int $type
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param int $status
     */
    public function __construct($themeId = 0, $type = 1, $startDate = null, $endDate = null, $status = 1)
    {
        $this->playerToChallenges = new ArrayCollection();

        if (!isset($startDate)) {
            $startDate = new \DateTime("now");
        }

        if (!isset($endDate)) {
            $endDate = new \DateTime("+1 days");
        }

        $this->setStartDate($startDate);
        $this->setEndDate($endDate);
        $this->setThemeId($themeId);
        $this->setType($type);
        $this->setStatus($status);

        return $this;
    }

    /**
     * @return int
     */
    public function getPlayersCount()
    {
        return count($this->getPlayerToChallenges());
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isInChallenge($user)
    {
        foreach($this->getPlayerToChallenges() as $playerToChallenge) {
            if ($playerToChallenge->getUser() == $user) {
                return true;
            }
        }

        return false;
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
     * @param User $user
     */

    public function join($user) {
        $playerToChallenge = new PlayerToChallenge();
        $playerToChallenge->setDate(new \DateTime("now"))
            ->setChallenge($this)
            ->setUser($user)
            ->setStatus(1);

        return $this;
    }

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
        $playerToChallenge->setChallenge($this);

        return $this;
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
