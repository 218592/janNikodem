<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    // php bin/console doctrine:fixtures:load
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $encoded = $this->encoder->encodePassword($admin, 'ratownik1');
        $admin->setPassword($encoded);
        $admin->setUsername('ratownik1');
        $admin->setEmail('ratownik1@gmail.com');
        $admin->setIsActive(true);
        $admin->setActiveTokenMail("1");
        $admin->setRoles(array('ROLE_ADMIN'));

        $admin2 = new User();
        $encoded = $this->encoder->encodePassword($admin2, 'ratownik2');
        $admin2->setPassword($encoded);
        $admin2->setUsername('ratownik2');
        $admin2->setEmail('ratownik2@gmail.com');
        $admin2->setIsActive(true);
        $admin2->setActiveTokenMail("1");
        $admin2->setRoles(array('ROLE_ADMIN'));

        $admin3 = new User();
        $encoded = $this->encoder->encodePassword($admin3, 'testkam');
        $admin3->setPassword($encoded);
        $admin3->setUsername('testkam');
        $admin3->setEmail('testkam@gmail.com');
        $admin3->setIsActive(true);
        $admin3->setActiveTokenMail("1");
        $admin3->setRoles(array('ROLE_ADMIN'));

        // $user = new User();
        // $encoded = $this->encoder->encodePassword($user, 'byk');
        // $user->setPassword($encoded);
        // $user->setUsername('byk');
        // $user->setEmail('admin@haha.com');
        // $user->setIsActive(true);
        // $user->setActiveTokenMail("0");
        // $admin->setRoles(array('ROLE_ADMIN'));

        // $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($admin2);
        $manager->persist($admin3);
        $manager->flush();
    }
}
