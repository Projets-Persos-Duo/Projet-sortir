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

class ThematiqueFixtures extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager): void
    {
        $this->addThematique('Cinéma',"1");
        $this->addThematique('Théâtre',"2");
        $this->addThematique('Danse',"3");
        $this->addThematique('Concert',"4");
        $this->addThematique('Conférence',"5");
        $this->addThematique('Sport',"6");
        $this->addThematique('Autres',"7");

        $manager->flush();
    }

    public function addThematique($theme, $ref) {
        $thematique = new Thematiques();
        $thematique->setTheme($theme);
        $this->manager->persist($thematique);
        $this->setReference("thematique-".$ref,$thematique);
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
