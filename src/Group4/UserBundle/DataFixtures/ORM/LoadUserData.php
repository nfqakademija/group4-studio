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
            $user->setEmail('andrew@photochallenge.com'.$i);
            $user->setPlainPassword('asdasd');
            $user->setEnabled(true);

            $manager->persist($user);

            $user = new User();
            $user->setUsername('Justas'.$i);
            $user->setEmail('justas@photochallenge.com'.$i);
            $user->setPlainPassword('asdasd');
            $user->setEnabled(true);

            $manager->persist($user);

            $user = new User();
            $user->setUsername('Marijus'.$i);
            $user->setEmail('marijus@photochallenge.com'.$i);
            $user->setPlainPassword('asdasd');
            $user->setEnabled(true);

            $manager->persist($user);
        }

        $manager->flush();

        $theme = new Theme();
        $theme->setApproved(true);
        $theme->setName('Music');

        $manager->persist($theme);

        $theme = new Theme();
        $theme->setApproved(true);
        $theme->setName('Relativity');

        $manager->persist($theme);

        $theme = new Theme();
        $theme->setApproved(true);
        $theme->setName('Eye');

        $manager->persist($theme);

        $theme = new Theme();
        $theme->setApproved(true);
        $theme->setName('Space');

        $manager->persist($theme);

        $theme = new Theme();
        $theme->setApproved(false);
        $theme->setName('NFQ');

        $manager->persist($theme);

        $type = new Type();
        $type->setDefault(true);
        $type->setName('5 min');
        $type->setUploadDuration('PT5M');
        $type->setVoteDuration('P1D');
        $type->setWaitDuration('PT1H');

        $manager->persist($type);

        $type = new Type();
        $type->setDefault(true);
        $type->setName('15 min');
        $type->setUploadDuration('PT15M');
        $type->setVoteDuration('P1D');
        $type->setWaitDuration('PT2H');

        $manager->persist($type);

        $type = new Type();
        $type->setDefault(true);
        $type->setName('1 hour');
        $type->setUploadDuration('PT1H');
        $type->setVoteDuration('P2D');
        $type->setWaitDuration('PT12H');

        $manager->persist($type);

        $type = new Type();
        $type->setDefault(true);
        $type->setName('1 day');
        $type->setUploadDuration('P1D');
        $type->setVoteDuration('P7D');
        $type->setWaitDuration('PT6H');

        $manager->persist($type);

        $type = new Type();
        $type->setDefault(false);
        $type->setName('1 year');
        $type->setUploadDuration('P1Y');
        $type->setVoteDuration('P3M');
        $type->setWaitDuration('P3M');

        $manager->persist($type);

        for($i = 1; $i <= 10; $i++) {
            $challenge = new Challenge($theme, $type, null, null, $i%4);
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