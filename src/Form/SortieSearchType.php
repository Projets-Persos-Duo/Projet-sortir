<?php

namespace App\Form;

use App\Data\SearchSortiesData;
use App\Entity\Campus;
use App\Entity\Thematiques;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class SortieSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('campus', EntityType::class, [
               'label'=>false,
               'required'=>false,
               'class'=>Campus::class,
               'expanded'=>true,
               'multiple'=>true,
               'label_attr' => [
                   'class' => 'checkbox-switch',
               ],

           ])
           ->add('contient', SearchType::class, [
               'label' => false,
               'required' => false,
               'attr' => [
                   'placeholder' => 'Le nom de la sortie contient',
               ]
           ])
           ->add('themes', EntityType::class, [
                'label'=> 'Thématiques',
                'required'=>false,
                'class'=>Thematiques::class,
                'expanded'=>true,
                'multiple'=>true
           ])
           ->add('entreDebut', DateTimeType::class, [
               'label' => 'Entre :',
               'html5' => true,
               'widget' => 'single_text'
           ])
           ->add('entreFin', DateTimeType::class, [
               'label' => 'Et :',
               'html5' => true,
               'widget' => 'single_text'
           ])
           ->add('queJOrganise', CheckboxType::class, ['label_attr' => ['class' => 'checkbox-switch',],])
           ->add('ouJeSuisInscrit', CheckboxType::class, ['label_attr' => ['class' => 'checkbox-switch',],])
           ->add('ouJeSuisPasInscrit', CheckboxType::class, ['label_attr' => ['class' => 'checkbox-switch',],])
           ->add('sortiesPassees', CheckboxType::class, ['label_attr' => ['class' => 'checkbox-switch',],])


       ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>SearchSortiesData::class,
            'method'=>'GET',
            'csrf_protection'=>false

        ]);
    }

    /**
     * @return string
     */

    public function getBlockPrefix()
    {
        return '';
    }

}