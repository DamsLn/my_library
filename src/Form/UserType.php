<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille',
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
                'required' => true,
                'mapped' => false
            ])
            ->add('register', SubmitType::class, [
                'label' => 'S\'inscrire',
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                $this->comparePasswords(...)
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    protected function comparePasswords(PostSubmitEvent $event): void
    {
        $password = $event->getForm()->get('password')->getData();
        $confirmPassword = $event->getForm()->get('confirmPassword')->getData();

        if ($password === $confirmPassword) {
            return;
        }

        $event->getForm()->get('password')->addError(new FormError('Les mots de passe ne sont pas identiques'));
        $event->getForm()->get('confirmPassword')->addError(new FormError('Les mots de passe ne sont pas identiques'));
    }
}
