<?php
namespace Group4\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Entity\Theme;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Group4\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
    {
    /**
    * @var ContainerInterface
    */
    private $container;

    /**
    * {@inheritDoc}
    */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            $user->setUsername('Andrew'.$i);
            $user->setEmail('asdasd@asdasd.asdasd'.$i);
            $user->setPlainPassword('asdasd');
            $user->setEnabled(true);

            $manager->persist($user);
        }

        $manager->flush();

        $theme = new Theme();
        $theme->setApproved(true);
        $theme->setName('Music');

        $manager->persist($theme);

        for($i = 1; $i <= 10; $i++) {
            $challenge = new Challenge($theme,1);
            $userRep = $manager->getRepository('UserBundle:User');
            $users = $userRep->findAll();
            foreach ($users as $user) {
                $challenge->join($user);
            }
            $manager->persist($challenge);
        }

        $manager->flush();
    }
}