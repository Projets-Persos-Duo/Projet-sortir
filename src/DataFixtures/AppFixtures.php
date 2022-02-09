<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Groupe;
use App\Entity\Lieu;
use App\Entity\Sortie;
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

        $lyon = new Ville();
        $lyon->setNom("Lyon");
        $lyon->setCodePostal("69000");
        $manager->persist($lyon);

        $strasbourg = new Ville();
        $strasbourg->setNom("Strasbourg");
        $strasbourg->setCodePostal("67000");
        $manager->persist($strasbourg);

        $lille = new Ville();
        $lille->setNom("Lille");
        $lille->setCodePostal("59000");
        $manager->persist( $lille);

        $poitiers = new Ville();
        $poitiers->setNom("Poitiers");
        $poitiers->setCodePostal("86000");
        $manager->persist($poitiers);


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

        //Lieu Fixtures

        $cinema1 = new Lieu();
        $cinema1->setNom("CGRNiort");
        $cinema1->setAdresse("Place de la Brèche. 79000 Niort");
        $cinema1->setLatitude("46.32242");
        $cinema1->setLongitude("-0.456899");
        $manager->persist($cinema1);

        $cinema2 = new Lieu();
        $cinema2->setNom("CGRMoulins");
        $cinema2->setAdresse("16, rue Marcellin Desboutin. 03000 Moulins");
        $cinema2->setLatitude("46.564351");
        $cinema2->setLongitude("3.339655");
        $manager->persist($cinema2);

        $theatre1 = new Lieu();
        $theatre1->setNom("Théatre Nantes");
        $theatre1->setAdresse("6 rue des Carmélites. 44000 Nantes");
        $theatre1->setLatitude(" 47.21666");
        $theatre1->setLongitude(" -1.55145");
        $manager->persist( $theatre1);

        $zoo1 = new Lieu();
        $zoo1->setNom("Zooparc de Beauval");
        $zoo1->setAdresse("Avenue du Blanc. 41110 St Aignan");
        $zoo1->setLatitude("47.24801");
        $zoo1->setLongitude("1.35304");
        $manager->persist($zoo1);

        $piscine1 = new Lieu();
        $piscine1->setNom("Piscine Rennes");
        $piscine1->setAdresse("10 boulevard Albert 1er. 35000 Rennes");
        $piscine1->setLatitude("48.08905");
        $piscine1->setLongitude("1.69100");
        $manager->persist( $piscine1);

        $piscine2 = new Lieu();
        $piscine2->setNom("Aquaworld Rennes");
        $piscine2->setAdresse("2 bis Rue du Bourg Nouveau. 35000 Rennes");
        $piscine2->setLatitude("48.11594");
        $piscine2->setLongitude("1.71841");
        $manager->persist($piscine2);

        $parc1 = new Lieu();
        $parc1->setNom("Parc et jardins du château");
        $parc1->setAdresse("90 All. de Lanniron. 29000 Quimper");
        $parc1->setLatitude("47.97592");
        $parc1->setLongitude("4.11059");
        $manager->persist(  $parc1);

        $parc2 = new Lieu();
        $parc2->setNom("Parc de la glisse");
        $parc2->setAdresse("131 Bd de Créac'h Gwen. 29000 Quimper");
        $parc2->setLatitude("47.97390");
        $parc2->setLongitude("-4.09912");
        $manager->persist($parc2);

        $parc3 = new Lieu();
        $parc3->setNom("Les machines de l'île");
        $parc3->setAdresse("Parc des Chantiers, Bd Léon Bureau. 44200 Nantes");
        $parc3->setLatitude("47.20738");
        $parc3->setLongitude("-1.56425");
        $manager->persist($parc3);

        $expo1 = new Lieu();
        $expo1->setNom("Muséum d'histoire Naturelle");
        $expo1->setAdresse("12 Rue Voltaire. 44000 Nantes");
        $expo1->setLatitude("47.21355");
        $expo1->setLongitude("-1.56474");
        $manager->persist($expo1);

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
