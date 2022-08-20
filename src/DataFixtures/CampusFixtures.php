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

class CampusFixtures extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager): void
    {
        $this->addCampus('Campus de Nantes',"1");
        $this->addCampus('Campus de Niort',"2");
        $this->addCampus('Campus de Quimper',"3");
        $this->addCampus('Campus de Rennes',"4");
        $this->addCampus('Campus en ligne',"5");

        $manager->flush();
    }

    public function addCampus($nom, $ref) {
        $campus = new Campus();
        $campus->setNom($nom);
        $this->manager->persist($campus);
        $this->setReference("campus-".$ref,$campus);
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
