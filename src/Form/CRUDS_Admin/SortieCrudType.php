<?php

namespace App\Form\CRUDS_Admin;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieCrudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date_annonce')
            ->add('heure_annonce')
            ->add('date_cloture')
            ->add('heure_cloture')
            ->add('date_debut')
            ->add('heure_debut')
            ->add('date_fin')
            ->add('heure_fin')
            ->add('raison_annulation')
            ->add('limite_participants')
            ->add('infos_sortie')
            ->add('theme')
            ->add('campus')
            ->add('groupe')
            ->add('organisateur')
            ->add('participants')
            ->add('lieu')
            ->add('lieuRDV')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
