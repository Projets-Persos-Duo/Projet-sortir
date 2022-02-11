<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo'
            ])
//            ->add('roles')
            ->add('email', EmailType::class)
            ->add('familyName', TextType::class, [
                'required' => false,
                'label' => 'Nom de famille'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('oldMDP', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'required' => false,
                'mapped' => false
            ])
            ->add('newMDP', RepeatedType::class, [
                'first_name' => 'newMDP',
                'second_name' => 'repeatMDP',
                'first_options' => ['required' => false, 'label' => 'Nouveau mot de passe'],
                'second_options' => ['required' => false, 'label' => 'Répéter mot de passe'],
                'mapped' => false,
                'required' => false,
                'type' => PasswordType::class
            ])
            ->add('campus')
//            ->add('groupes')
//            ->add('sortiesParticipees')
//            ->add('groupesGeres')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
