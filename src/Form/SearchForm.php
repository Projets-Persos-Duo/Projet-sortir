<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class SearchForm extends AbstractType
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
           ])
//           ->add('thematiques', EntityType::class, [
//                'label'=>false,
//                'required'=>false,
//                'class'=>Thematiques::class,
//                'expanded'=>true,
//                'multiple'=>true
//           ])



       ;
    }


    public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'data_class'=>SearchData::class,
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