<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\User;
use App\Entity\Message;
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
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'Password1'
        ));
        $admin->setEmail('admin@mail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setStatus(1);

        $manager->persist($admin);

        $user = new User();
        $user->setUsername('user');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Password0'
        ));
        $user->setEmail('user@mail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setStatus(1);

        $manager->persist($user);

        $trickGroup1 = new TrickGroup();
        $trickGroup1->setName('Groupe1');

        $manager->persist($trickGroup1);

        $trickGroup2 = new TrickGroup();
        $trickGroup2->setName('Groupe2');

        $manager->persist($trickGroup2);

        for ($i = 1; $i <= 45; $i++) {
            $trick = new Trick();
            $trick->setName('Trick' . $i);
            $trick->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi malesuada ullamcorper metus non blandit. Etiam non neque eu elit malesuada tincidunt eget et nulla. Vestibulum rutrum arcu massa, sed pretium mauris maximus ac. In hac habitasse platea dictumst. Curabitur pharetra, nisl vel mollis tempor, tellus urna ornare dui, vel egestas ipsum enim venenatis turpis. Aenean et turpis ac urna commodo malesuada non quis dolor. Fusce rhoncus, metus quis sodales vestibulum, augue tellus placerat tortor, in iaculis turpis sem quis urna. Donec nec augue vel enim varius consectetur consequat eget felis. Ut rutrum metus ligula, in venenatis lectus sollicitudin ac.');
            $trick->setTrickGroup($trickGroup1);
            $trick->setUser($user);
            $date = new \DateTime();
            $date->add(new DateInterval('P'.$i.'D'));
            $trick->setCreationDate($date);
            $trick->setUpdateDate($date);
            for ($j = 1; $j <= 45; $j++) {
                $message = new Message();
                $message->setContent('Lorem ipsum...');
                $message->setUser($user);
                $message->setTrick($trick);

                $manager->persist($message);
            }
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
