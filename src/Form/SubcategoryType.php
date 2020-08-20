<?php

namespace App\Form;

use App\Entity\Subcategory;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SubcategoryType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $this->entityManager->getRepository(Category::class)->findAll();
        $builder
            ->add('titre', TextType::class, array('label' => false, 'required' => true))
            ->add('category', ChoiceType::class, [
                'label' => false,
                'choices' => $data,
                'choice_label' => function ($category, $key, $value) {
                    /** @var Category $category */
                    return strtoupper($category->getLibelle());
                },
                'choice_attr' => function ($category, $key, $value) {
                    return ['class' => 'category' . strtolower($category->getLibelle())];
                },
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subcategory::class,
            'csrf_protection' => false,
        ]);
    }
}
