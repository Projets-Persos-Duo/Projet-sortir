<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Groupe;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Thematiques;
use App\Entity\User;
use App\Entity\Ville;
use DateTime;
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
        $cinema = new Thematiques();
        $cinema->setTheme('Cinéma');
        $manager->persist($cinema);

        $theatre = new Thematiques();
        $theatre->setTheme('Théâtre');
        $manager->persist($theatre);

        $danse = new Thematiques();
        $danse->setTheme('Danse');
        $manager->persist($danse);

        $concert= new Thematiques();
        $concert->setTheme('Concert');
        $manager->persist($concert);

        $exposition= new Thematiques();
        $exposition->setTheme('Exposition');
        $manager->persist($exposition);

        $conference= new Thematiques();
        $conference->setTheme('Conférence');
        $manager->persist($conference);

        $sport= new Thematiques();
        $sport->setTheme('Sport');
        $manager->persist($sport);

        $autres= new Thematiques();
        $autres->setTheme('Autres');
        $manager->persist($autres);


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

        $moulins = new Ville();
        $moulins->setNom("Moulins");
        $moulins->setCodePostal("03000");
        $manager->persist($moulins);

        $saintAignan = new Ville();
        $saintAignan->setNom("Saint-Aignan");
        $saintAignan->setCodePostal("41110");
        $manager->persist($saintAignan);


        //Campus Fixtures
        $campusNantes = new Campus();
        $campusNantes->setNom('Campus de Nantes');
        $manager->persist($campusNantes);

        $campusNiort = new Campus();
        $campusNiort->setNom('Campus de Niort');
        $manager->persist($campusNiort);

        $campusQuimper = new Campus();
        $campusQuimper->setNom('Campus de Quimper');
        $manager->persist($campusQuimper);

        $campusRennes = new Campus();
        $campusRennes->setNom('Campus de Rennes');
        $manager->persist($campusRennes);

        $campusEnLigne = new Campus();
        $campusEnLigne->setNom('Campus en ligne');
        $manager->persist($campusEnLigne);


        //Lieu Fixtures
        $cinema1 = new Lieu();
        $cinema1->setVille($niort);
        $cinema1->setNom("CGRNiort");
        $cinema1->setAdresse("Place de la Brèche");
        $cinema1->setLatitude(46.32242);
        $cinema1->setLongitude(-0.456899);
        $manager->persist($cinema1);

        $cinema2 = new Lieu();
        $cinema2->setVille($moulins);
        $cinema2->setNom("CGRMoulins");
        $cinema2->setAdresse("16, rue Marcellin Desboutin");
        $cinema2->setLatitude(46.564351);
        $cinema2->setLongitude(3.339655);
        $manager->persist($cinema2);

        $theatre1 = new Lieu();
        $theatre1->setVille($nantes);
        $theatre1->setNom("Théatre Nantes");
        $theatre1->setAdresse("6 rue des Carmélites");
        $theatre1->setLatitude(47.21666);
        $theatre1->setLongitude(-1.55145);
        $manager->persist( $theatre1);

        $zoo1 = new Lieu();
        $zoo1->setVille($saintAignan);
        $zoo1->setNom("Zooparc de Beauval");
        $zoo1->setAdresse("Avenue du Blanc");
        $zoo1->setLatitude(47.24801);
        $zoo1->setLongitude(1.35304);
        $manager->persist($zoo1);

        $piscine1 = new Lieu();
        $piscine1->setVille($rennes);
        $piscine1->setNom("Piscine Rennes");
        $piscine1->setAdresse("10 boulevard Albert 1er");
        $piscine1->setLatitude(48.08905);
        $piscine1->setLongitude(1.69100);
        $manager->persist( $piscine1);

        $piscine2 = new Lieu();
        $piscine2->setVille($rennes);
        $piscine2->setNom("Aquaworld Rennes");
        $piscine2->setAdresse("2 bis Rue du Bourg Nouveau");
        $piscine2->setLatitude(48.11594);
        $piscine2->setLongitude(1.71841);
        $manager->persist($piscine2);

        $parc1 = new Lieu();
        $parc1->setVille($quimper);
        $parc1->setNom("Parc et jardins du château");
        $parc1->setAdresse("90 All. de Lanniron");
        $parc1->setLatitude(47.97592);
        $parc1->setLongitude(4.11059);
        $manager->persist($parc1);

        $parc2 = new Lieu();
        $parc2->setVille($quimper);
        $parc2->setNom("Parc de la glisse");
        $parc2->setAdresse("131 Bd de Créac'h Gwen");
        $parc2->setLatitude(47.97390);
        $parc2->setLongitude(-4.09912);
        $manager->persist($parc2);

        $parc3 = new Lieu();
        $parc3->setVille($nantes);
        $parc3->setNom("Les machines de l'île");
        $parc3->setAdresse("Parc des Chantiers, Bd Léon Bureau");
        $parc3->setLatitude(47.20738);
        $parc3->setLongitude(-1.56425);
        $manager->persist($parc3);

        $expo1 = new Lieu();
        $expo1->setVille($nantes);
        $expo1->setNom("Muséum d'histoire Naturelle");
        $expo1->setAdresse("12 Rue Voltaire");
        $expo1->setLatitude(47.21355);
        $expo1->setLongitude(-1.56474);
        $manager->persist($expo1);


        //User Fixtures
        $admin = new User();
        $admin->setUsername('admin');
        $password = $this->hasher->hashPassword($admin, '123123');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setIsAdmin(true);
        $admin->setIsActive(true);
        $admin->setCampus($campusEnLigne);
        $manager->persist($admin);

        $toto = new User();
        $toto->setUsername('toto');
        $toto->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($toto, '123123');
        $toto->setPassword($password);
        $toto->setIsAdmin(false);
        $toto->setIsActive(true);
        $toto->setCampus($campusEnLigne);
        $manager->persist($toto);

        $sego = new User();
        $sego->setUsername('Sego');
        $sego->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($sego, '123123');
        $sego->setPassword($password);
        $sego->setIsAdmin(false);
        $sego->setIsActive(true);
        $sego->setCampus($campusNiort);
        $manager->persist($sego);

        $fred = new User();
        $fred->setUsername('Fred');
        $fred->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($fred, '123123');
        $fred->setPassword($password);
        $fred->setIsAdmin(false);
        $fred->setIsActive(true);
        $fred->setCampus($campusQuimper);
        $manager->persist($fred);

        $ivo = new User();
        $ivo->setUsername('Ivo');
        $ivo->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($ivo, '123123');
        $ivo->setPassword($password);
        $ivo->setIsAdmin(false);
        $ivo->setIsActive(true);
        $ivo->setCampus($campusNantes);
        $manager->persist($ivo);

        $lulu = new User();
        $lulu->setUsername('Lulu');
        $lulu->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($lulu, '123123');
        $lulu->setPassword($password);
        $lulu->setIsAdmin(false);
        $lulu->setIsActive(true);
        $lulu->setCampus($campusNantes);
        $manager->persist($lulu);


        //Groupe Fixtures
        $groupe1 = new Groupe();
        $groupe1->setProprietaire($toto);
        $manager->persist($groupe1);


        //Sortie Fixtures
        $sortieEnCours = new Sortie();
        $sortieEnCours->setNom("Natation débutant");
        $sortieEnCours->setDateAnnonce(new DateTime('2022-02-02'));
        $sortieEnCours->setHeureAnnonce(new DateTime('20:00:00'));
        $sortieEnCours->setDateDebut(new DateTime('2022-20-02'));
        $sortieEnCours->setHeureDebut(new DateTime('14:00:00'));
        $sortieEnCours->setDateFin(new DateTime('2022-20-02'));
        $sortieEnCours->setHeureFin(new DateTime('16:00:00'));
        $sortieEnCours->setRaisonAnnulation('null');
        $sortieEnCours->setLimiteParticipants(5);
        $sortieEnCours->setInfosSortie("Cours initiation débutant");
        $sortieEnCours->setTheme($sport);
        $sortieEnCours->setCampus($campusRennes);
        $sortieEnCours->setOrganisateur($lulu);
        $sortieEnCours->setLieu($piscine1);
        $sortieEnCours->addParticipant($toto);
        $manager->persist($sortieEnCours);

        $sortieAnnulee = new Sortie();
        $sortieAnnulee->setNom("Star Wars");
        $sortieAnnulee->setDateAnnonce(new DateTime('2022-02-02'));
        $sortieAnnulee->setHeureAnnonce(new DateTime('20:00:00'));
        $sortieAnnulee->setDateDebut(new DateTime('2022-20-02'));
        $sortieAnnulee->setHeureDebut(new DateTime('20:00:00'));
        $sortieAnnulee->setDateFin(new DateTime('2022-20-02'));
        $sortieAnnulee->setHeureFin(new DateTime('22:00:00'));
        $sortieAnnulee->setRaisonAnnulation("Cinéma fermé");
        $sortieAnnulee->setLimiteParticipants(20);
        $sortieAnnulee->setInfosSortie("Séance en 3D");
        $sortieAnnulee->setTheme($cinema);
        $sortieAnnulee->setCampus($campusNiort);
        $sortieAnnulee->setOrganisateur($sego);
        $sortieAnnulee->setLieu($cinema1);
        $manager->persist($sortieAnnulee);

        $sortieCloturee = new Sortie();
        $sortieCloturee->setNom("Concert U2");
        $sortieCloturee->setDateAnnonce(new DateTime('2022-02-02'));
        $sortieCloturee->setHeureAnnonce(new DateTime('19:00:00'));
        $sortieCloturee->setDateCloture(new DateTime('2022-05-02'));
        $sortieCloturee->setHeureCloture(new DateTime('14:32:00'));
        $sortieCloturee->setDateDebut(new DateTime('2022-19-02'));
        $sortieCloturee->setHeureDebut(new DateTime('21:00:00'));
        $sortieCloturee->setDateFin(new DateTime('2022-19-02'));
        $sortieCloturee->setHeureFin(new DateTime('23:00:00'));
        $sortieCloturee->setLimiteParticipants(4);
        $sortieCloturee->setInfosSortie("Places assises");
        $sortieCloturee->setTheme($concert);
        $sortieCloturee->setCampus($campusNantes);
        $sortieCloturee->setOrganisateur($ivo);
        $sortieCloturee->setLieu($theatre1);
        $sortieCloturee->addParticipant($lulu);
        $sortieCloturee->addParticipant($ivo);
        $sortieCloturee->addParticipant($fred);
        $sortieCloturee->addParticipant($sego);
        $manager->persist($sortieCloturee);

        $sortieArchivee = new Sortie();
        $sortieArchivee->setNom("Visite du château");
        $sortieArchivee->setDateAnnonce(new DateTime('2021-12-12'));
        $sortieArchivee->setHeureAnnonce(new DateTime('19:00:00'));
        $sortieArchivee->setDateDebut(new DateTime('2022-01-01'));
        $sortieArchivee->setHeureDebut(new DateTime('10:00:00'));
        $sortieArchivee->setDateFin(new DateTime('2022-01-01'));
        $sortieArchivee->setHeureFin(new DateTime('11:00:00'));
        $sortieArchivee->setLimiteParticipants(5);
        $sortieArchivee->setInfosSortie("Pensez à prendre un parapluie pour la visite des jardins");
        $sortieArchivee->setTheme($autres);
        $sortieArchivee->setCampus($campusQuimper);
        $sortieArchivee->setOrganisateur($fred);
        $sortieArchivee->setLieu($parc1);
        $manager->persist($sortieArchivee);



        $manager->flush();
    }

}
