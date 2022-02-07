<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label'=>'Nom de la sortie'])
            ->add('date_debut', DateType::class, ['label'=>'Date de la sortie',
                                     'html5'=>true,
                                    'widget'=>'single_text' ])
            ->add('heure_debut', TimeType::class, ['label'=>'Heure de la sortie',
                                     'html5'=>true,
                                    'widget'=>'single_text' ])
            ->add('date_fin', DateType::class, ['label'=>'Date de fin de la sortie',
                'html5'=>true,
                'widget'=>'single_text'])
            ->add('heure_fin', TimeType::class, ['label'=>'Heure de fin de la sortie',
                                     'html5'=>true,
                                    'widget'=>'single_text' ])
           /* ->add('duree', NumberType::class, ['label'=>'Durée'])*/
            ->add('date_cloture', DateType::class, ['label'=>'Date limite d\'inscription',
                                    'html5'=>true,
                                      'widget'=>'single_text'])
            ->add('heure_cloture', TimeType::class, ['label'=>'Veuillez indiquer l\'heure de cloture des inscriptions',
                                    'html5'=>true,
                                   'widget'=>'single_text' ])
            ->add('limite_participants', NumberType::class, ['label'=>'Nombre de places'])
            ->add('infos_sortie', TextareaType::class, ['label'=>'Description et infos'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
