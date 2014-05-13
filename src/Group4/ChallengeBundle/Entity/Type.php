<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\TypeRepository")
 */
class Type
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
     * @var string
     *
     * @ORM\Column(name="uploadDuration", type="string", length=255)
     */
    private $uploadDuration;

    /**
     * @var string
     *
     * @ORM\Column(name="waitDuration", type="string", length=255)
     */
    private $waitDuration;

    /**
     * @var string
     *
     * @ORM\Column(name="voteDuration", type="string", length=255)
     */
    private $voteDuration;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private $default;

    /**
     * Get id
     *
     * @return integer 
     */

    /**
     * @var ArrayCollection|Challenge[]
     *
     * @ORM\OneToMany(targetEntity="Challenge", mappedBy="type")
     */
    private $challenges;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Type
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
     * Set uploadDuration
    }
*
* @param string $uploadDuration
* @return Type
*/
    public function setUploadDuration($uploadDuration)
    {
        $this->uploadDuration = $uploadDuration;

        return $this;
    }

    /**
     * Get uploadDuration
     *
     * @return string 
     */
    public function getUploadDuration()
    {
        return $this->uploadDuration;
    }

    /**
     * Set waitDuration
     *
     * @param string $waitDuration
     * @return Type
     */
    public function setWaitDuration($waitDuration)
    {
        $this->waitDuration = $waitDuration;

        return $this;
    }

    /**
     * Get waitDuration
     *
     * @return string 
     */
    public function getWaitDuration()
    {
        return $this->waitDuration;
    }

    /**
     * Set voteDuration
     *
     * @param string $voteDuration
     * @return Type
     */
    public function setVoteDuration($voteDuration)
    {
        $this->voteDuration = $voteDuration;

        return $this;
    }

    /**
     * Get voteDuration
     *
     * @return string 
     */
    public function getVoteDuration()
    {
        return $this->voteDuration;
    }

    /**
     * @param boolean $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return boolean
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Get UploadDurationInterval
     * Converts string to dateInterval
     *
     * @return \DateInterval
     */
    public function getUploadDurationInterval(){
        return new \DateInterval($this->getUploadDuration());
    }

    /**
     * Get WaitDurationInterval
     * Converts string to dateInterval
     *
     * @return \DateInterval
     */
    public function getWaitDurationInterval(){
        return new \DateInterval($this->getWaitDuration());
    }

    /**
     * Get VoteDurationInterval
     * Converts string to dateInterval
     *
     * @return \DateInterval
     */
    public function getVoteDurationInterval(){
        return new \DateInterval($this->getVoteDuration());
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
     * @return Type
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
