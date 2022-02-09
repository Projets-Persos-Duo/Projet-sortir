<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Thematiques;
use App\Entity\User;
use App\Entity\Ville;
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
        //Thematique Fixtures
        foreach (
            [
                'Cinéma',
                'Théâtre',
                'Danse',
                'Concert',
                'Exposition',
                'Loisirs',
                'Conférence',
                'Restaurant',
                'Sport',
                'Autres'
            ] as $theme) {
            $thematique = new Thematiques();
            $thematique->setTheme($theme);
            $manager->persist($thematique);
        }


        //Ville Fixtures
        $nantes = new Ville();
        $nantes->setNom("Nantes");
        $nantes->setCodePostal("44000");
        $manager->persist($nantes);

        $niort = new Ville();
        $niort->setNom("Niort");
        $niort->setCodePostal("79000");
        $manager->persist($niort);

        $rennes = new Ville();
        $rennes->setNom("Rennes");
        $rennes->setCodePostal("35000");
        $manager->persist($rennes);

        $quimper = new Ville();
        $quimper->setNom("Quimper");
        $quimper->setCodePostal("29000");
        $manager->persist($quimper);



        //Campus Fixtures
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

        //User Fixtures
        $admin = new User();
        $admin->setUsername('admin');

        $password = $this->hasher->hashPassword($admin, '123');
        $admin->setPassword($password);

        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setIsAdmin(true);
        $admin->setIsActive(true);
        $admin->setCampus($campus_en_ligne);

        $manager->persist($admin);


        $toto = new User();
        $toto->setUsername('toto');
        $toto->setRoles(['ROLE_USER']);

        $password = $this->hasher->hashPassword($toto, '123');
        $toto->setPassword($password);

        $toto->setIsAdmin(false);
        $toto->setIsActive(true);
        $toto->setCampus($campus_en_ligne);

        $manager->persist($toto);


        $manager->flush();
    }
}
