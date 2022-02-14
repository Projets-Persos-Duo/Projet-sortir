<?php

namespace App\Form\CRUDS_Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCrudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
        'label' => 'Pseudo'])
           // ->add('roles')
            ->add('password', PasswordType::class, [
               'label' => 'Mot de passe'])
            ->add('email', EmailType::class)
            ->add('familyName',TextType::class, [
                'required' => false,
                'label' => 'Nom de famille'])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => false,])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false])
            ->add('isAdmin', ChoiceType::class, [
                'expanded'=>true,
                'multiple'=>false,
                'choices'=>['oui'=>true, 'non'=>false],
                ])
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
