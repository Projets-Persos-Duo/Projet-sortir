<?php

namespace App\Form;

use App\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomGroupe', TextType::class, ['label'=>'Nom du groupe :','mapped'=>false])
            ->add('proprietaire', TextType::class, ['label'=>'Nom du propriétaire :'])
            ->add('membres', CollectionType::class, ['label'=>'Liste des membres :',
                'allow_add'=>true,
                'allow_delete'=>true,
                'entry_type'=>'string'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
}
