<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\User;
use DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('admin');
        $user1->setEmail('admin@mail.com');
        $user1->setPassword($this->passwordEncoder->encodePassword(
            $user1,
            'Password0'
        ));
        $user1->setRoles(['ROLE_ADMIN']);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('user');
        $user2->setEmail('user@mail.com');
        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'Password0'
        ));
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        $trickGroup1 = new TrickGroup();
        $trickGroup1->setName('Groupe1');
        $manager->persist($trickGroup1);

        $trickGroup2 = new TrickGroup();
        $trickGroup2->setName('Groupe2');
        $manager->persist($trickGroup2);

        for ($i = 1; $i <= 45; $i++) {
            $trick = new Trick();
            $trick->setName('Trick' . $i);
            $trick->setDescription('Lorem Ipsum...');
            $trick->setTrickGroup($trickGroup1);
            $trick->setUser($user1);
            $date = new \DateTime();
            $date->add(new DateInterval('P'.$i.'D'));
            $trick->setCreationDate($date);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
