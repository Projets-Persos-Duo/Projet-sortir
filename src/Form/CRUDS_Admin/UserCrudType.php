<?php

namespace App\Form\CRUDS_Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCrudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('roles')
            ->add('password')
            ->add('email')
            ->add('familyName')
            ->add('firstName')
            ->add('telephone')
            ->add('isAdmin')
            ->add('isActive')
            ->add('campus')
            ->add('groupes')
            ->add('sortiesParticipees')
            ->add('groupesGeres')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
