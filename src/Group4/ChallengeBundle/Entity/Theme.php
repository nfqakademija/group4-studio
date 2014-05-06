<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Theme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\ThemeRepository")
 */
class Theme
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean")
     */
    private $approved;

    /**
     * @var ArrayCollection|Challenge[]
     *
     * @ORM\OneToMany(targetEntity="Challenge", mappedBy="theme")
     */
    private $challenges;


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
     * Set name
     *
     * @param string $name
     * @return Theme
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    public function __toString()
    {
        return $this->getName();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->challenges = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add challenges
     *
     * @param \Group4\ChallengeBundle\Entity\Challenge $challenges
     * @return Theme
     */
    public function addChallenge(\Group4\ChallengeBundle\Entity\Challenge $challenges)
    {
        $this->challenges[] = $challenges;

        return $this;
    }

    /**
     * Remove challenges
     *
     * @param \Group4\ChallengeBundle\Entity\Challenge $challenges
     */
    public function removeChallenge(\Group4\ChallengeBundle\Entity\Challenge $challenges)
    {
        $this->challenges->removeElement($challenges);
    }

    /**
     * Get challenges
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChallenges()
    {
        return $this->challenges;
    }
}
