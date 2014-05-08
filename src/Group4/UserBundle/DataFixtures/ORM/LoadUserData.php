<?php
namespace Group4\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Group4\ChallengeBundle\Entity\Challenge;
use Group4\ChallengeBundle\Entity\Photo;
use Group4\ChallengeBundle\Entity\Theme;
use Group4\ChallengeBundle\Entity\Type;
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

        $type = new Type();
        $type->setDefault(true);
        $type->setName('5');
        $type->setUploadDuration('PT5M');
        $type->setVoteDuration('P1D');
        $type->setWaitDuration('PT1H');

        $manager->persist($type);

        for($i = 1; $i <= 10; $i++) {
            $challenge = new Challenge($theme, $type);
            $userRep = $manager->getRepository('UserBundle:User');
            $users = $userRep->findAll();
            foreach ($users as $user) {
                $playerToChallenge = $challenge->join($user);
                $image = new Photo();
                $image->setImageName("534bf9856553a.png");
                $image->setUser($user);
                $manager->persist($image);
                $playerToChallenge->setStatus(1);
                $playerToChallenge->setImage($image);
            }
            $manager->persist($challenge);
        }

        $manager->flush();
    }
}