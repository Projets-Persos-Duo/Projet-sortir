<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $campus_en_ligne = null;
        foreach (
            [
                'Campus de Nantes',
                'Campus de Niort',
                'Campus de Quimper',
                'Campus de Rennes',
                'Campus en ligne'
            ] as $nom) {
            $campus = new Campus();
            $campus->setNom($nom);

            if($nom === 'Campus en ligne') $campus_en_ligne= $campus;

            $manager->persist($campus);
        }

        $admin = new User();
        $admin->setUsername('admin');

        $password = $this->hasher->hashPassword($admin, '123');
        $admin->setPassword($password);

        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsAdmin(true);
        $admin->setIsActive(true);
        $admin->setCampus($campus_en_ligne);

        $manager->persist($admin);


        $toto = new User();
        $toto->setUsername('toto');

        $password = $this->hasher->hashPassword($toto, '123');
        $toto->setPassword($password);

        $toto->setIsAdmin(false);
        $toto->setIsActive(true);
        $toto->setCampus($campus_en_ligne);

        $manager->persist($toto);

        $manager->flush();
    }
}
