<?php

namespace App\Form;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rolename', TextType::class, array('label' => false, 'required' => true))
            ->add('rolecode', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'ROLE USER'   =>'ROLE_USER' ,
                    'ROLE ADMIN'  => 'ROLE_ADMIN',
                    'ROLE ANNONCEUR'=>'ROLE_ANNONCEUR',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
