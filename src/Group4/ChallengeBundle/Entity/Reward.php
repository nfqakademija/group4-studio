<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reward
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\RewardRepository")
 */
class Reward
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
     * @var ArrayCollection
     *
     * @ORM\OneToOne(targetEntity="PlayerToChallenge", mappedBy="reward")
     * @ORM\JoinColumn(name="player_to_challenge", referencedColumnName="id", nullable=true)
     */
    private $playerToChallenge;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;

    public function __construct($p2c, $value)
    {
        $this->setPlayerToChallenge($p2c);
        $this->setValue($value);

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
     * Set playerToChallenge
     *
     * @param integer $playerToChallenge
     * @return Reward
     */
    public function setPlayerToChallenge($playerToChallenge)
    {
        $this->playerToChallenge = $playerToChallenge;

        return $this;
    }

    /**
     * Get playerToChallenge
     *
     * @return integer 
     */
    public function getPlayerToChallenge()
    {
        return $this->playerToChallenge;
    }

    /**
     * Set value
     *
     * @param integer $value
     * @return Reward
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }
}
