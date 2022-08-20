<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Groupe;
use App\Entity\Lieu;
use App\Entity\Photo;
use App\Entity\Sortie;
use App\Entity\Thematiques;
use App\Entity\User;
use App\Entity\Ville;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class VilleFixtures extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager): void
    {
        $this->addVille('Nantes',"44000","1");
        $this->addVille('Niort',"79000","2");
        $this->addVille('Rennes',"35000","3");
        $this->addVille('Quimper',"29000","4");
        $this->addVille('Lyon',"69000","5");
        $this->addVille('Strasbourg',"67000","6");
        $this->addVille('Lille',"59000","7");
        $this->addVille('Poitiers',"86000","8");
        $this->addVille('Moulins',"03000","9");
        $this->addVille('Saint-Aignan',"41110","10");

        $manager->flush();
    }

    public function addVille($nom, $codePostal, $ref) {
        $ville = new Ville();
        $ville->setNom($nom);
        $ville->setCodePostal($codePostal);
        $this->manager->persist($ville);
        $this->setReference("ville-".$ref,$ville);
    }

    public static function getGroups(): array
    {
        return ['init'];
    }

    public function getOrder(): int
    {
        return 10;
    }
}
