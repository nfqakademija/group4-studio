<?php
// src/Acme/UserBundle/Entity/User.php

namespace Group4\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="image_id", type="integer", nullable=true)
     */
    protected $imageId;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}