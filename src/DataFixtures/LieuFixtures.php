<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager): void
    {
        // Récupérer les villes via le repository
        $villeRepository = $manager->getRepository(Ville::class);

        $this->addLieu($villeRepository->findOneBy(['nom'=>'Niort']), "CGRNiort", "16, rue Marcellin Desboutin", 46.32242, -0.456899, "1");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Moulins']), "CGRMoulins", "Place de la Brèche", 46.564351, 3.339655, "2");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Nantes']), "Théatre Nantes", "6 rue des Carmélites", 47.21666, -1.55145, "3");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Saint-Aignan']), "Zooparc de Beauval", "Avenue du Blanc", 47.24801, 1.35304, "4");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Rennes']), "Piscine Rennes", "10 boulevard Albert 1er", 48.08905, 1.69100, "5");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Rennes']), "Aquaworld Rennes", "2 bis Rue du Bourg Nouveau", 48.11594, 1.71841, "6");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Quimper']), "Parc et jardins du château", "90 All. de Lanniron", 47.97592, 4.11059, "7");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Quimper']), "Parc de la glisse", "Place de 131 Bd de Créac'h Gwen Brèche", 47.97390, -4.09912, "8");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Nantes']), "Les machines de l'île", "Parc des Chantiers, Bd Léon Bureau", 47.20738, -1.56425, "9");
        $this->addLieu($villeRepository->findOneBy(['nom'=>'Nantes']), "Muséum d'histoire Naturelle", "12 Rue Voltaire", 47.21355, -1.56474, "10");

        $manager->flush();
    }

    public function addLieu($ville, $nom, $adresse, $latitude, $longitude, $ref) {
        $lieu = new Lieu();
        $lieu->setVille($ville);
        $lieu->setNom($nom);
        $lieu->setAdresse($adresse);
        $lieu->setLatitude($latitude);
        $lieu->setLongitude($longitude);
        $this->manager->persist($lieu);
        $this->setReference("lieu-".$ref,$lieu);
    }

    public static function getGroups(): array
    {
        return ['init'];
    }

    public function getOrder(): int
    {
        return 20;
    }
}
