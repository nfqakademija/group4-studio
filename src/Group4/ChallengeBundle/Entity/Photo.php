<?php

namespace Group4\ChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Group4\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Group4\ChallengeBundle\Entity\PhotoRepository")
 * @Vich\Uploadable
 */
class Photo
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
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="players_challenge_image", fileNameProperty="imageName")
     *
     * @var File $image
     */
    private $image;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToOne(targetEntity="PlayerToChallenge", mappedBy="image")
     */
    private $playerToChallenges;

    /**
     * @ORM\Column(type="string", length=255, name="image_name")
     *
     * @var string $imageName
     */
    protected $imageName;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Group4\UserBundle\Entity\User", inversedBy="photos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Photo
     */



    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Photo
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
     * Set playerToChallenges
     *
     * @param \Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenges
     * @return Photo
     */
    public function setPlayerToChallenges(\Group4\ChallengeBundle\Entity\PlayerToChallenge $playerToChallenges = null)
    {
        $this->playerToChallenges = $playerToChallenges;

        return $this;
    }

    /**
     * Get playerToChallenges
     *
     * @return \Group4\ChallengeBundle\Entity\PlayerToChallenge 
     */
    public function getPlayerToChallenges()
    {
        return $this->playerToChallenges;
    }
}
