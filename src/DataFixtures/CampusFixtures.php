<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

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
