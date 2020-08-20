<?php
// /src/Form/UserType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, array('label' => false, 'required' => true) )
            ->add('addresse', TextType::class, array('label' => false))
            ->add('telmobile', TextType::class, array('label' => false))
            ->add('codepost', TextType::class, array('label' => false, 'required' => true))
            ->add('telfixe', TextType::class, array('label' => false))
            ->add('codepost', TextType::class, array('label' => false))
            ->add('email', EmailType::class, array('label' => false, 'required' => true))
            ->add('username', TextType::class, array('label' => false, 'required' => true))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => false),
                'second_options' => array('label' => false),
            ))
            ->add('imageFile',FileType::class, ['label'=>false, "required"=>false]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}