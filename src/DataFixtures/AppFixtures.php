<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\TrickPhoto;
use App\Entity\TrickVideo;
use App\Entity\User;
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
        $admin->setUsername('Brian');
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'OpenClassrooms1234'
        ));
        $admin->setEmail('admin@mail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setStatus(1);

        $manager->persist($admin);

        $user = new User();
        $user->setUsername('Jean');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Password0'
        ));
        $user->setEmail('user@mail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setStatus(1);

        $manager->persist($user);

        $straightAirs = new TrickGroup();
        $straightAirs->setName('Straight airs');

        $manager->persist($straightAirs);

        $grabs = new TrickGroup();
        $grabs->setName('Grabs');

        $manager->persist($grabs);

        $slides = new TrickGroup();
        $slides->setName('Slides');

        $manager->persist($slides);

        $flipsAndInvertedRotations = new TrickGroup();
        $flipsAndInvertedRotations->setName('Flips and inverted rotations');

        $manager->persist($flipsAndInvertedRotations);

        $stalls = new TrickGroup();
        $stalls->setName('Stalls');

        $manager->persist($stalls);

        $ollie = new Trick();
        $ollie->setName('Ollie');
        $ollie->setDescription('Un trick dans lequel le snowboarder s\'élance du tail de la planche et dans les airs.');
        $ollie->setTrickGroup($straightAirs);
        $ollie->setUser($user);
        $ollie->setCreationDate(new \DateTime());
        $ollie->setUpdateDate($ollie->getCreationDate());
        $ollie->setMainPhoto('ollie1.jpg');

        $photo = new TrickPhoto();
        $photo->setName('ollie2.jpg');
        $photo->setTrick($ollie);

        $ollie->addPhoto($photo);

        $manager->persist($photo);

        $photo = new TrickPhoto();
        $photo->setName('ollie3.jpg');
        $photo->setTrick($ollie);

        $manager->persist($photo);

        $ollie->addPhoto($photo);

        $photo = new TrickPhoto();
        $photo->setName('ollie4.jpg');
        $photo->setTrick($ollie);

        $manager->persist($photo);

        $ollie->addPhoto($photo);

        $video = new TrickVideo();
        $video->setName('ollie1.mp4');
        $video->setTrick($ollie);

        $manager->persist($video);

        $ollie->addVideo($video);

        $video = new TrickVideo();
        $video->setName('ollie2.mp4');
        $video->setTrick($ollie);

        $manager->persist($video);

        $ollie->addVideo($video);

        $video = new TrickVideo();
        $video->setName('ollie3.mp4');
        $video->setTrick($ollie);

        $manager->persist($video);

        $ollie->addVideo($video);

        $manager->persist($ollie);

        $shifty = new Trick();
        $shifty->setName('Shifty');
        $shifty->setDescription('Un trick aérien dans lequel un snowboarder fait contre-rotation le haut de son corps afin de déplacer sa planche d\'environ 90 ° par rapport à sa position normale sous lui, puis ramène la planche à sa position d\'origine avant d\'atterrir. Ce tour peut être réalisé en frontside ou backside, mais aussi en variation avec d\'autres tricks and spins.');
        $shifty->setTrickGroup($straightAirs);
        $shifty->setUser($user);
        $shifty->setCreationDate(new \DateTime());
        $shifty->setUpdateDate($shifty->getCreationDate());
        $shifty->setMainPhoto('shifty.jpg');

        $manager->persist($shifty);

        $oneTwo = new Trick();
        $oneTwo->setName('One-Two');
        $oneTwo->setDescription('Un trick dans lequel la main avant du cavalier attrape le bord du talon derrière son pied arrière.');
        $oneTwo->setTrickGroup($grabs);
        $oneTwo->setUser($user);
        $oneTwo->setCreationDate(new \DateTime());
        $oneTwo->setUpdateDate($oneTwo->getCreationDate());
        $oneTwo->setMainPhoto('oneTwo.jpg');

        $manager->persist($oneTwo);

        $crail = new Trick();
        $crail->setName('Crail');
        $crail->setDescription('La main arrière saisit le bord des orteils devant le pied avant tandis que la jambe arrière est désossée.[1] Alternativement, certains considèrent toute prise arrière devant le pied avant sur le bord des orteils comme une prise crail, classant une prise au nez comme un « crail de nez » ou un « vrai crail ».');
        $crail->setTrickGroup($grabs);
        $crail->setUser($user);
        $crail->setCreationDate(new \DateTime());
        $crail->setUpdateDate($crail->getCreationDate());
        $crail->setMainPhoto('crail.jpg');

        $manager->persist($oneTwo);

        $nosePick = new Trick();
        $nosePick->setName('Nose-pick');
        $nosePick->setDescription('Caler sur un objet avec le nez de la planche à neige, tout en saisissant la face avant, puis sauter en arrière de l\'objet dans le saut que vous avez effectué.');
        $nosePick->setTrickGroup($stalls);
        $nosePick->setUser($user);
        $nosePick->setCreationDate(new \DateTime());
        $nosePick->setUpdateDate($nosePick->getCreationDate());
        $nosePick->setMainPhoto('nosePick.jpg');

        $manager->persist($nosePick);

        $bluntStall = new Trick();
        $bluntStall->setName('Blunt-stall');
        $bluntStall->setDescription('Imitant le skateboard, et similaire à un décrochage de planche, ce tour est effectué en calant sur un objet avec la queue de la planche (décrochage émoussé) ou le nez de la planche (décrochage du nez). Distingué d\'un décrochage avant ou d\'un décrochage arrière car pendant le décrochage, la majeure partie du snowboard sera positionnée au-dessus de l\'obstacle et du point de contact.');
        $bluntStall->setTrickGroup($stalls);
        $bluntStall->setUser($user);
        $bluntStall->setCreationDate(new \DateTime());
        $bluntStall->setUpdateDate($bluntStall->getCreationDate());
        $bluntStall->setMainPhoto('bluntStall.jpg');

        $manager->persist($bluntStall);

        $boardslide = new Trick();
        $boardslide->setName('Boardslide');
        $boardslide->setDescription(' Une glissade effectuée où le pied de tête du cycliste passe au-dessus du rail à l\'approche, avec son snowboard se déplaçant perpendiculairement le long du rail ou d\'un autre obstacle. Lors de l\'exécution d\'un boardslide frontside, le snowboarder fait face à la montée. Lors de l\'exécution d\'un backside boardslide, un snowboarder fait face à la descente. C\'est souvent déroutant pour les nouveaux riders qui apprennent le trick car avec un boardslide frontside vous reculez et avec un boardslide backside vous avancez.');
        $boardslide->setTrickGroup($slides);
        $boardslide->setUser($user);
        $boardslide->setCreationDate(new \DateTime());
        $boardslide->setUpdateDate($boardslide->getCreationDate());
        $boardslide->setMainPhoto('boardslide.jpg');

        $manager->persist($boardslide);

        $noseblunt = new Trick();
        $noseblunt->setName('Noseblunt');
        $noseblunt->setDescription('Une glissade effectuée où le pied arrière du cycliste passe au-dessus du rail à l\'approche, avec sa planche à neige se déplaçant perpendiculairement et le pied avant directement au-dessus du rail ou d\'un autre obstacle (comme un noseslide). Lors de l\'exécution d\'un frontside noseblunt, le snowboarder fait face à la descente. Lors de l\'exécution d\'un noseblunt arrière, le snowboarder fait face à la montée.');
        $noseblunt->setTrickGroup($slides);
        $noseblunt->setUser($user);
        $noseblunt->setCreationDate(new \DateTime());
        $noseblunt->setUpdateDate($noseblunt->getCreationDate());
        $noseblunt->setMainPhoto('noseblunt.jpg');

        $manager->persist($noseblunt);

        $wildcat = new Trick();
        $wildcat->setName('Wildcat');
        $wildcat->setDescription('Un backflip effectué sur un saut droit, avec un axe de rotation dans lequel le snowboarder effectue un flip vers l\'arrière, comme une roue de charrette. Un double chat sauvage est appelé un superchat.');
        $wildcat->setTrickGroup($flipsAndInvertedRotations);
        $wildcat->setUser($user);
        $wildcat->setCreationDate(new \DateTime());
        $wildcat->setUpdateDate($wildcat->getCreationDate());
        $wildcat->setMainPhoto('wildcat.jpg');

        $manager->persist($wildcat);

        $chicane = new Trick();
        $chicane->setName('Chicane');
        $chicane->setDescription('Une chicane est un tour rarement fait qui consiste à faire un frontside 180 avec un front flip sur l\'axe X. À l\'opposé du rouleau 90, la chicane est frontale 90, repliée avant, 90 degrés de plus pour atterrir, ou vice versa.');
        $chicane->setTrickGroup($flipsAndInvertedRotations);
        $chicane->setUser($user);
        $chicane->setCreationDate(new \DateTime());
        $chicane->setUpdateDate($chicane->getCreationDate());
        $chicane->setMainPhoto('chicane.jpg');

        $manager->persist($chicane);

        $manager->flush();
    }
}
