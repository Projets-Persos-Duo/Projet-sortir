<?php

namespace App\Data;


use App\Entity\Sortie;
use App\Entity\Thematiques;


class SearchData
{

    /**
     * @var Sortie []
     */
    public array $campus =[];

    public ?string $contient = null;

    /**
     * @var Thematiques[]
     */
    public array $themes = [];

    public ?\DateTime $entreDebut = null;

    public ?\DateTime $entreFin = null;

    public ?bool $queJOrganise = null;

    public ?bool $ouJeSuisInscrit = null;

    public ?bool $ouJeSuisPasInscrit = null;

    public ?bool $sortiesPassees = null;


}